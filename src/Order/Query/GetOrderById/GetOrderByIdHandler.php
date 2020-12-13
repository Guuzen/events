<?php

declare(strict_types=1);

namespace App\Order\Query\GetOrderById;

use App\Infrastructure\Persistence\DatabaseSerializer\DatabaseSerializer;
use Doctrine\DBAL\Connection;

final class GetOrderByIdHandler
{
    private $connection;

    private $serializer;

    public function __construct(Connection $connection, DatabaseSerializer $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    public function execute(string $orderId): array
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
        $stmt->bindValue('order_id', $orderId);
        $stmt->execute();

        /** @var string $orderData */
        $orderData = $stmt->fetchOne();

        return $this->serializer->decode($orderData);
    }
}
