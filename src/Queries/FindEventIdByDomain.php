<?php

namespace App\Queries;

use Doctrine\DBAL\Connection;

// TODO maybe make gateway?
final class FindEventIdByDomain
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(string $domain): string
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
        /** @var array{id: string} */
        $result = $stmt->fetch();

        return $result['id'];
    }
}
