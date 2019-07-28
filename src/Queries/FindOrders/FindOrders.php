<?php

namespace App\Queries\FindOrders;

use Doctrine\DBAL\Connection;

final class FindOrders
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
                "order".id as id,
                ticket.status as status,
                "user".id as user_id,
                "order".paid as paid,
                "order".maked_at as maked_at,
                "order".sum ->> \'amount\' as sum,
                "order".sum -> \'currency\' ->> \'code\' as currency,
                "user".contacts ->> \'phone\' as phone,
                "user".contacts ->> \'email\' as email,   
                "user".full_name ->> \'first_name\' as first_name,
                "user".full_name ->> \'last_name\' as last_name
            from
                "order"
            left join ticket_tariff on "order".tariff_id = ticket_tariff.id
            left join "user" on "order".user_id = "user".id
            left join ticket on "order".product_id = ticket.id
        ');

        return $stmt->fetchAll();
    }
}
