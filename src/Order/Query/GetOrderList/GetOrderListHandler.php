<?php

declare(strict_types=1);

namespace App\Order\Query\GetOrderList;

use App\Infrastructure\Persistence\DatabaseSerializer\DatabaseSerializer;
use App\Order\Query\OrderResource;
use Doctrine\DBAL\Connection;

final class GetOrderListHandler
{
    private $connection;

    private $serializer;

    public function __construct(Connection $connection, DatabaseSerializer $serializer)
    {
        $this->connection   = $connection;
        $this->serializer   = $serializer;
    }

    // TODO should all exceptions be in docblock? Handlers may be used not only by http. What will happen on exception
    public function execute(GetOrderList $query): array
    {
        $stmt = $this->connection->prepare(
            '
            select
                json_agg(orders)
            from (
                select
                    *
                from
                    "order"
                where
                    "order".event_id = :event_id
            ) as orders
        '
        );
        $stmt->bindValue('event_id', $query->eventId);
        $stmt->execute();

        /** @var string|false $ordersData */
        $ordersData = $stmt->fetchOne();
        if ($ordersData === false) {
            throw new OrderListNotFound('');
        }

        return $this->serializer->deserializeToArray($ordersData, OrderResource::class);
    }
}
