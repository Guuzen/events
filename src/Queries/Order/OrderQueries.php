<?php

namespace App\Queries\Order;

use App\Queries\Order\FindOrderById\OrderById;
use App\Queries\Order\FindOrderById\OrderByIdNotFound;
use App\Queries\Order\FindOrdersInList\OrderInList;
use Doctrine\DBAL\Connection;

final class OrderQueries
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return OrderInList[]
     */
    public function findInList(string $eventId): array
    {
        $stmt = $this->connection->prepare('
            select
                row_to_json("order") as json
            from (
                select
                    "order".event_id as "event_id",
                    "order".id as id,
                    tariff_details.tariff_type as product,
                    ticket.id as "product_id",
                    "order".tariff_id as "tariff_id",
                    "user".id as "user_id",
                    "order".paid as paid,
                    "order".cancelled as "cancelled",
                    "order".maked_at as "maked_at",
                    "order".sum ->> \'amount\' as sum,
                    "order".sum -> \'currency\' ->> \'code\' as currency,
                    "order".discount -> \'amount\' ->> \'amount\' as discount,
                    "user".contacts ->> \'phone\' as phone,
                    "user".contacts ->> \'email\' as email,   
                    "user".full_name ->> \'first_name\' as "first_name",
                    "user".full_name ->> \'last_name\' as "last_name",
                    ticket.created_at as created_at
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
                    event.id = :event_id
            ) as "order"
        ');
        $stmt->bindParam('event_id', $eventId);
        $stmt->execute();

        $orders = [];
        /** @psalm-var array{json: string} $orderData */
        foreach ($stmt->fetchAll() as $orderData) {
            /** @var OrderInList */
            $order    = $this->connection->convertToPHPValue($orderData['json'], OrderInList::class);
            $orders[] = $order;
        }

        return $orders;
    }

    /**
     * @return OrderById|OrderByIdNotFound
     */
    public function findById(string $orderId)
    {
        $stmt = $this->connection->prepare('
            select
                row_to_json("order") as json
            from (
                select
                    "order".event_id as "event_id",
                    "order".id as id,
                    tariff_details.tariff_type as product,
                    ticket.id as "product_id",
                    "order".tariff_id as "tariff_id",
                    "user".id as "user_id",
                    "order".paid as paid,
                    "order".cancelled as "cancelled",
                    "order".maked_at as "maked_at",
                    "order".sum ->> \'amount\' as sum,
                    "order".sum -> \'currency\' ->> \'code\' as currency,
                    "order".discount -> \'amount\' ->> \'amount\' as discount,
                    "user".contacts ->> \'phone\' as phone,
                    "user".contacts ->> \'email\' as email,   
                    "user".full_name ->> \'first_name\' as "first_name",
                    "user".full_name ->> \'last_name\' as "last_name",
                    ticket.created_at as created_at
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
            ) as "order"                
        ');
        $stmt->bindValue('order_id', $orderId);
        $stmt->execute();

        /** @psalm-var array{json: string}|false */
        $orderData = $stmt->fetch();
        if (false === $orderData) {
            return new OrderByIdNotFound();
        }

        /** @var OrderById */
        $order = $this->connection->convertToPHPValue($orderData['json'], OrderById::class);

        return $order;
    }
}
