<?php

namespace App\Queries\FindEvents;

use Doctrine\DBAL\Connection;

final class FindEvents
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(): array
    {
        $stmt = $this->connection->query('
            select
                *
            from
                event_config
        ');

        return $stmt->fetchAll();
    }
}
