<?php

namespace App\Order\Action;

use App\Infrastructure\Http\AppRequest;
use App\Infrastructure\Http\AppRequestValidator;
use App\Queries\FindEventIdByDomain;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Serializer;

final class PlaceOrderRequestResolver implements ArgumentValueResolverInterface
{
    private $serializer;

    private $validator;

    private $findEventIdByDomain;

    public function __construct(
        Serializer $serializer,
        AppRequestValidator $validator,
        FindEventIdByDomain $findEventIdByDomain
    ) {
        $this->serializer          = $serializer;
        $this->validator           = $validator;
        $this->findEventIdByDomain = $findEventIdByDomain;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return PlaceOrder::class === $argument->getType();
    }

    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        $argumentType = $argument->getType();
        if (null === $argumentType) {
            throw new \Exception();
        }

        /** @var array $appRequestData */
        $appRequestData             = $this->serializer->decode((string) $request->getContent(), 'json');
        $appRequestData['event_id'] = ($this->findEventIdByDomain)($request->getHost());
        /** @var AppRequest $appRequest */
        $appRequest                 = $this->serializer->denormalize($appRequestData, $argumentType);

        yield from $this->validator->validate($appRequest);
    }
}
