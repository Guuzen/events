<?php

namespace App\Common;

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
        return is_subclass_of($argument->getType(), AppRequest::class);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        if (Request::METHOD_GET === $request->getMethod()) {
            $params     = $request->query->all();
            /** @var AppRequest $appRequest */
            $appRequest = $this->serializer->denormalize($params, $argument->getType());
        } else {
            /** @var AppRequest $appRequest */
            $appRequest = $this->serializer->deserialize($request->getContent(), $argument->getType(), 'json');
        }

        yield from $this->validator->validate($appRequest);
    }
}
