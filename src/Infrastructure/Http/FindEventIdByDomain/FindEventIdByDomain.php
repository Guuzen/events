<?php

namespace App\Infrastructure\Http\FindEventIdByDomain;

use Doctrine\DBAL\Connection;

final class FindEventIdByDomain
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return string|EventIdByDomainNotFound
     */
    public function __invoke(string $domain)
    {
        $stmt = $this->connection->prepare('
            select
                id
            from
                event_config
            where
                event_config.domain = :domain
        ');
        $stmt->bindParam('domain', $domain);
        $stmt->execute();
        /** @psalm-var array{id: string}|false */
        $result = $stmt->fetch();
        if (false === $result) {
            return new EventIdByDomainNotFound();
        }

        return $result['id'];
    }
}
