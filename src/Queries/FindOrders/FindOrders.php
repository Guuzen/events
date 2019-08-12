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

    public function __invoke(string $eventId): array
    {
        $stmt = $this->connection->prepare('
            select
                "order".event_id as "eventId",
                "order".id as id,
                 product.type ->> \'type\' as product,
                "user".id as "userId",
                "order".paid as paid,
                "order".maked_at as "makedAt",
                "order".sum ->> \'amount\' as sum,
                "order".sum -> \'currency\' ->> \'code\' as currency,
                "user".contacts ->> \'phone\' as phone,
                "user".contacts ->> \'email\' as email,   
                "user".full_name ->> \'first_name\' as "firstName",
                "user".full_name ->> \'last_name\' as "lastName"
            from
                "order"
            left join
                "user" on "order".user_id = "user".id
            left join
                product on "order".product_id = product.id
            left join
                event on "order".event_id = event.id
            where
                event.id = :event_id
                
        ');
        $stmt->bindParam('event_id', $eventId);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
