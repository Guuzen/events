<?php

declare(strict_types=1);

namespace App\Order\Query\FindOrderById;

use App\Infrastructure\Persistence\DatabaseSerializer\DatabaseSerializer;
use App\Order\Query\OrderResource;
use Doctrine\DBAL\Connection;

// TODO getOrderByIdHandler ?
final class FindOrderByIdHandler
{
    private $connection;

    private $serializer;

    public function __construct(Connection $connection, DatabaseSerializer $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    public function execute(FindOrderById $query): OrderResource
    {
        $stmt = $this->connection->prepare(
            '
            select
                row_to_json("order")
            from (
                select
                    *
                from
                    "order"
                where
                    "order".id = :order_id                 
            ) as "order"
        '
        );
        $stmt->bindValue('order_id', $query->orderId);
        $stmt->execute();

        /** @var string $orderData */
        $orderData = $stmt->fetchOne();

        return $this->serializer->deserialize($orderData, OrderResource::class);
    }
}
