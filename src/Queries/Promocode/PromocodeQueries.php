<?php

namespace App\Queries\Promocode;

use Doctrine\DBAL\Connection;

final class PromocodeQueries
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findAll(): array
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
