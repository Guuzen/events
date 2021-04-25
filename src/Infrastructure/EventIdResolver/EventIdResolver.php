<?php

declare(strict_types=1);

namespace App\Infrastructure\EventIdResolver;

use App\Model\Event\EventId;
use Doctrine\DBAL\Connection;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class EventIdResolver implements ArgumentValueResolverInterface
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
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
        $domain = $request->getHost();

        $stmt = $this->connection->prepare(
            '
            select
                id
            from
                event_domain
            where
                event_domain.domain = :domain
        '
        );

        $stmt->bindValue('domain', $domain);
        $stmt->execute();
        /** @psalm-var array{id: string}|false */
        $result = $stmt->fetchAssociative();
        if (false === $result) {
            throw new EventIdByDomainNotFound(
                \sprintf('Domain: %s not found', $domain)
            );
        }

        /** @psalm-suppress MixedArgument */
        yield new EventId($result['id']);
    }
}
