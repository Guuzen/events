<?php

namespace App\Infrastructure\Http\RequestResolver;

use App\Infrastructure\Http\Openapi\OpenapiValidator;
use Exception;
use Generator;
use GuzzleHttp\Psr7\ServerRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Serializer;

final class AppRequestResolver implements ArgumentValueResolverInterface
{
    private $serializer;

    private $validator;

    public function __construct(Serializer $serializer, OpenapiValidator $validator)
    {
        $this->serializer = $serializer;
        $this->validator  = $validator;
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
            $routeParams = $request->attributes->get('_route_params');
            $queryParams = $request->query->all();
            $params = \array_merge($queryParams, $routeParams);
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
        /** @var string $requestMethod */
        $requestMethod = mb_strtolower($request->getMethod());
        $psr7request   = new ServerRequest(
            $requestMethod,
            $request->getRequestUri(),
            $request->headers->all(),
            $request->getContent(),
            $request->getProtocolVersion(),
            $request->server->all()
        );
        $psr7request   = $psr7request->withQueryParams($request->query->all());

        $this->validator->validate($psr7request);
    }
}
