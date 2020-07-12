<?php

namespace App\Infrastructure\Http\RequestResolver;

use Exception;
use Generator;
use GuzzleHttp\Psr7\ServerRequest;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\Schema\BreadCrumb;
use League\OpenAPIValidation\Schema\Exception\SchemaMismatch;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

final class AppRequestResolver implements ArgumentValueResolverInterface
{
    private $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $argumentType = $argument->getType();
        if (null === $argumentType) {
            throw new Exception();
        }

        return \is_subclass_of($argumentType, AppRequest::class);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        $argumentType = $argument->getType();
        if (null === $argumentType) {
            throw new Exception();
        }

        if (Request::METHOD_GET === $request->getMethod()) {
            $params = $request->query->all();
            /** @var AppRequest $appRequest */
            $appRequest = $this->serializer->denormalize($params, $argumentType);
            $this->validate($request);
        } else {
            $requestContent = $request->getContent();
            $this->validate($request);
            /** @var AppRequest $appRequest */
            $appRequest = $this->serializer->deserialize($requestContent, $argumentType, 'json');
        }

        yield $appRequest;
    }

    private function validate(Request $request): void
    {
        $requestContent = $request->getContent();
        $requestUri     = $request->getRequestUri();
        /** @var string $requestMethod */
        $requestMethod    = mb_strtolower($request->getMethod());
        $psr7request      = new ServerRequest(
            $requestMethod,
            $requestUri,
            $request->headers->all(),
            $requestContent,
            $request->getProtocolVersion(),
            $request->server->all()
        );
        $psr7request      = $psr7request->withQueryParams($request->query->all());
        $yamlFile         = '/var/www/html/openapi/stoplight.yaml';
        $psr7validator    = (new \League\OpenAPIValidation\PSR7\ValidatorBuilder)
            ->fromYamlFile($yamlFile)
            ->getRoutedRequestValidator();
        $operationAddress = new OperationAddress($request->getPathInfo(), $requestMethod);
        try {
            $psr7validator->validate(
                $operationAddress,
                $psr7request
            );
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
