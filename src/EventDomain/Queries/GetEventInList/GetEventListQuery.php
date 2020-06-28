<?php

declare(strict_types=1);

namespace App\EventDomain\Queries\GetEventInList;

use Doctrine\DBAL\Connection;

final class GetEventListQuery
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
                event_domain
        ');

        return $stmt->fetchAll();
    }
}
