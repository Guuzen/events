<?php

namespace App\Queries\FindPromocodes;

use Doctrine\DBAL\Connection;

final class FindPromocodes
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    // TODO filter all queries by event id
    public function __invoke(): array
    {
        $stmt = $this->connection->query('
            select
                *
            from
                regular_promocode            
        ');

        return $stmt->fetchAll();
    }
}
