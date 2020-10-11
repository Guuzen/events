<?php

declare(strict_types=1);

namespace App\Order\Query\FindOrderById;

use App\Infrastructure\Persistence\JsonFromDatabaseDeserializer\JsonFromDatabaseDeserializer;
use Doctrine\DBAL\Connection;

final class FindOrderByIdHandler
{
    private $connection;

    private $deserializer;

    public function __construct(Connection $connection, JsonFromDatabaseDeserializer $deserializer)
    {
        $this->connection = $connection;
        $this->deserializer = $deserializer;
    }

    /**
     * @psalm-return array{
     *      price: array{
     *          amount: string,
     *          currency: string,
     *      },
     *      promocodeId: ?string,
     * }
     *
     * @psalm-suppress MixedReturnTypeCoercion
     */
    public function execute(FindOrderById $query): array
    {
        $stmt = $this->connection->prepare('
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
        ');
        $stmt->bindValue('order_id', $query->orderId);
        $stmt->execute();

        /** @var string $orderData */
        $orderData = $stmt->fetchOne();

        return $this->deserializer->deserialize($orderData);
    }
}
