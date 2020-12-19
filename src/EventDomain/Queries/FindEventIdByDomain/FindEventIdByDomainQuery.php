<?php

namespace App\EventDomain\Queries\FindEventIdByDomain;

use Doctrine\DBAL\Connection;

final class FindEventIdByDomainQuery
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(string $domain): string
    {
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
        $stmt->bindParam('domain', $domain);
        $stmt->execute();
        /** @psalm-var array{id: string}|false */
        $result = $stmt->fetchAssociative();
        if (false === $result) {
            throw new EventIdByDomainNotFound(
                \sprintf('Domain: %s not found', $domain)
            );
        }

        return $result['id'];
    }
}
