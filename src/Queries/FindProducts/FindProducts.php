<?php

namespace App\Queries\FindProducts;

use Doctrine\DBAL\Connection;

final class FindProducts
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
                product.type ->> \'type\' as type,
                product.created_at as "created_at",
                ticket.number,
                product.reserved
            from
                product
            left join
                ticket on ticket.id = product.id
        ');

        return $stmt->fetchAll();
    }
}
