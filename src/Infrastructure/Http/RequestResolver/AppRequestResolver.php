<?php

namespace App\Infrastructure\Http\RequestResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Serializer;

final class AppRequestResolver implements ArgumentValueResolverInterface
{
    private $serializer;

    private $validator;

    public function __construct(Serializer $serializer, AppRequestValidator $validator)
    {
        $this->serializer = $serializer;
        $this->validator  = $validator;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $argumentType = $argument->getType();
        if (null === $argumentType) {
            throw new \Exception();
        }

        return is_subclass_of($argumentType, AppRequest::class);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        $argumentType = $argument->getType();
        if (null === $argumentType) {
            throw new \Exception();
        }

        if (Request::METHOD_GET === $request->getMethod()) {
            $params     = $request->query->all();
            /** @var AppRequest $appRequest */
            $appRequest = $this->serializer->denormalize($params, $argumentType);
        } else {
            /** @var AppRequest $appRequest */
            $appRequest = $this->serializer->deserialize($request->getContent(), $argumentType, 'json');
        }

        yield from $this->validator->validate($appRequest);
    }
}
