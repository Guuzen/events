<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Openapi;

use App\Infrastructure\Http\RequestResolver\InvalidAppRequest;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use League\OpenAPIValidation\Schema\BreadCrumb;
use League\OpenAPIValidation\Schema\Exception\SchemaMismatch;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

final class OpenapiValidator
{
    private $openapiSchema;

    public function __construct(OpenapiSchema $openapiSchema)
    {
        $this->openapiSchema = $openapiSchema;
    }

    public function validateRequest(ServerRequestInterface $request): void
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

    public function validateResponse(Request $request, Response $response): void
    {
        /** @psalm-suppress PossiblyNullReference */
        $uri    = $request->getRequestUri();
        $method = mb_strtolower($request->getMethod());
        /** @psalm-suppress PossiblyFalseArgument */
        $operation = new OperationAddress($uri, $method);

        /** @psalm-suppress PossiblyFalseArgument */
        $validator = (new ValidatorBuilder)
            ->fromSchema($this->openapiSchema->asCebeOpenapi())
            ->getResponseValidator();

        $psr17Factory   = new Psr17Factory();
        $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
        $psr7Response   = $psrHttpFactory->createResponse($response);

        try {
            $validator->validate($operation, $psr7Response);
        } catch (ValidationFailed $validationFailed) {
            $previosException = $validationFailed->getPrevious();
            if ($previosException instanceof SchemaMismatch) {
                /** @var BreadCrumb $breadCrumb */
                $breadCrumb      = $previosException->dataBreadCrumb();
                $breadCrumbChain = $breadCrumb->buildChain();
                $message         = $validationFailed->getMessage() . ' '
                    . $previosException->getMessage() . ' '
                    . \implode('.', $breadCrumbChain);
            } else {
                $message = $validationFailed->getMessage();
            }
            throw new \RuntimeException($message);
        }
    }
}
