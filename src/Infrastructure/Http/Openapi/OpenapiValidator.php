<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Openapi;

use App\Infrastructure\Http\RequestResolver\InvalidAppRequest;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use League\OpenAPIValidation\Schema\BreadCrumb;
use League\OpenAPIValidation\Schema\Exception\SchemaMismatch;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

final class OpenapiValidator
{
    private $openapiSchema;

    public function __construct(OpenapiSchema $openapiSchema)
    {
        $this->openapiSchema = $openapiSchema;
    }

    public function validate(ServerRequestInterface $request): void
    {
        $validatorBuilder = new ValidatorBuilder();
        $validator        = $validatorBuilder
            ->fromSchema($this->openapiSchema->asCebeOpenapi())
            ->getServerRequestValidator();

        try {
            $validator->validate($request);
        } catch (ValidationFailed $validationFailed) {
            $previosException = $validationFailed->getPrevious();
            if ($previosException instanceof SchemaMismatch) {
                /** @var BreadCrumb $breadCrumb */
                $breadCrumb      = $previosException->dataBreadCrumb();
                $breadCrumbChain = $breadCrumb->buildChain();
                $violation       = new ConstraintViolation(
                    $previosException->getMessage(),
                    null,
                    [],
                    'some',
                    \implode('.', $breadCrumbChain),
                    'some invalid value'
                );
            } else {
                $violation = new ConstraintViolation(
                    $validationFailed->getMessage(),
                    null,
                    [],
                    'some',
                    '',
                    'some invalid value'
                );
            }

            $constraintViolations = new ConstraintViolationList([$violation]);
            throw new InvalidAppRequest($constraintViolations);
        }
    }
}
