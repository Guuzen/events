<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\EventIdResolver;

use App\Common\Error;
use App\Event\Model\EventId;
use App\EventDomain\FindEventIdByDomain;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class EventIdResolver implements ArgumentValueResolverInterface
{
    private $findEventIdByDomain;

    public function __construct(FindEventIdByDomain $findEventIdByDomain)
    {
        $this->findEventIdByDomain = $findEventIdByDomain;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $argumentType = $argument->getType();
        if (null === $argumentType) {
            throw new \RuntimeException(
                \sprintf('There is no type for argument %s', $argument->getName())
            );
        }

        return EventId::class === $argumentType;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        $eventId = ($this->findEventIdByDomain)($request->getHost());
        if ($eventId instanceof Error) {
            throw new \RuntimeException(); // TODO remove errors and make exceptions great again ?
        }

        /** @psalm-suppress MixedArgument */
        yield new EventId($eventId);
    }
}
