<?php
declare(strict_types=1);

namespace App\Common;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AppRequestResolver implements ArgumentValueResolverInterface
{
    private $denormalizer;

    private $serializer;

    private $validator;

    public function __construct(
        DenormalizerInterface $denormalizer,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->denormalizer = $denormalizer;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return is_subclass_of($argument->getType(), AppRequest::class);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        /** @var AppRequest $appRequest */
        if ($request->getMethod() === Request::METHOD_GET) {
            $params = $request->query->all();
            $appRequest = $this->denormalizer->denormalize($params, $argument->getType());
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
