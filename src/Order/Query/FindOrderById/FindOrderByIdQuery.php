<?php

declare(strict_types=1);

namespace App\Order\Query\FindOrderById;

use Doctrine\DBAL\Connection;

final class FindOrderByIdQuery
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return array|OrderNotFound
     */
    public function __invoke(string $orderId)
    {
        $stmt = $this->connection->prepare('
            select
                "order".event_id as "eventId",
                "order".id as id,
                tariff_details.tariff_type as product,
                ticket.id as "productId",
                "order".tariff_id as "tariffId",
                "user".id as "userId",
                "order".paid as paid,
                "order".cancelled as "cancelled",
                "order".maked_at as "makedAt",
                "order".sum ->> \'amount\' as sum,
                "order".sum -> \'currency\' ->> \'code\' as currency,
                "order".discount -> \'amount\' ->> \'amount\' as discount,
                "user".contacts ->> \'phone\' as phone,
                "user".contacts ->> \'email\' as email,   
                "user".full_name ->> \'first_name\' as "firstName",
                "user".full_name ->> \'last_name\' as "lastName",
                ticket.created_at as "createdAt"
            from
                "order"
            left join
                "user" on "order".user_id = "user".id
            left join
                ticket on "order".id = ticket.order_id
            left join
                event on "order".event_id = event.id
            left join
                tariff_details on tariff_details.id = "order".tariff_id
            where
                "order".id = :order_id                
        ');
        $stmt->bindValue('order_id', $orderId);
        $stmt->execute();

        /** @var array|false $order */
        $order = $stmt->fetch();
        if (false === $order) {
            return new OrderNotFound();
        }

        return $order;
    }
}
