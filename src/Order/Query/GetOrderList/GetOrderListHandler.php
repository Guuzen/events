<?php

declare(strict_types=1);

namespace App\Order\Query\GetOrderList;

use App\Infrastructure\Persistence\JsonFromDatabaseDeserializer\JsonFromDatabaseDeserializer;
use Doctrine\DBAL\Connection;

final class GetOrderListHandler
{
    private $connection;

    private $deserializer;

    public function __construct(Connection $connection, JsonFromDatabaseDeserializer $deserializer)
    {
        $this->connection = $connection;
        $this->deserializer = $deserializer;
    }

    // TODO should all exceptions be in docblock? Handlers may be used not only by http. What will happen on exception

    /**
     * @psalm-return array{
     *      array{
     *          price: array{
     *              amount: string,
     *              currency: string,
     *          },
     *          promocodeId: ?string,
     *      }
     * }
     *
     * @psalm-suppress MixedReturnTypeCoercion
     */
    public function execute(GetOrderList $query): array
    {
        $stmt = $this->connection->prepare('
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
        ');
        $stmt->bindValue('event_id', $query->eventId);
        $stmt->execute();

        /** @var string|false $ordersData */
        $ordersData = $stmt->fetchColumn();
        if ($ordersData === false) {
            throw new OrderListNotFound('');
        }

        return $this->deserializer->deserialize($ordersData);
    }
}
