<?php

declare(strict_types=1);

namespace App\Order\Query\GetOrderList;

use App\Infrastructure\Persistence\DatabaseSerializer\DatabaseSerializer;
use Doctrine\DBAL\Connection;

final class GetOrderListHandler
{
    private $connection;

    private $serializer;

    public function __construct(Connection $connection, DatabaseSerializer $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    // TODO should all exceptions be in docblock? Handlers may be used not only by http. What will happen on exception

    /**
     * @psalm-suppress MixedReturnTypeCoercion
     *
     * @return array<int, array>
     */
    public function execute(string $eventId): array
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
        $stmt->bindValue('event_id', $eventId);
        $stmt->execute();

        /** @var string|false $ordersData */
        $ordersData = $stmt->fetchOne();
        if ($ordersData === false) {
            throw new OrderListNotFound('');
        }

        return $this->serializer->decode($ordersData);
    }
}
