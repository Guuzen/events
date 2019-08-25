<?php

namespace App\Common;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AppRequestResolver implements ArgumentValueResolverInterface
{
    private $serializer;

    private $validator;

    public function __construct(Serializer $serializer, ValidatorInterface $validator)
    {
        $this->serializer   = $serializer;
        $this->validator    = $validator;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return is_subclass_of($argument->getType(), AppRequest::class);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        /* @var AppRequest $appRequest */
        if (Request::METHOD_GET === $request->getMethod()) {
            $params     = $request->query->all();
            $appRequest = $this->serializer->denormalize($params, $argument->getType());
        } else {
            $appRequest = $this->serializer->deserialize($request->getContent(), $argument->getType(), 'json');
        }

        $errors = $this->validator->validate($appRequest);
        if (count($errors) > 0) {
            throw new InvalidAppRequest($errors);
        }

        yield $appRequest;
    }
}
