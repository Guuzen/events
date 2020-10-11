<?php

declare(strict_types=1);

namespace App\Promocode\Query\GetPromocodeById;

use App\Infrastructure\Persistence\JsonFromDatabaseDeserializer\JsonFromDatabaseDeserializer;
use Doctrine\DBAL\Connection;

final class GetPromocodeByIdHandler
{
    private $connection;

    private $deserializer;

    public function __construct(Connection $connection, JsonFromDatabaseDeserializer $deserializer)
    {
        $this->connection   = $connection;
        $this->deserializer = $deserializer;
    }

    /**
     * @psalm-return array{
     *      discount: array,
     * }
     *
     * @psalm-suppress MixedReturnTypeCoercion
     */
    public function execute(GetPromocodeById $query): array
    {
        $stmt = $this->connection->prepare(
            '
            select
                row_to_json(promocode)
            from (
                select
                    *
                from
                    promocode
                where
                    promocode.id = :promocode_id                 
            ) as promocode
            '
        );
        $stmt->bindValue('promocode_id', $query->promocodeId);
        $stmt->execute();

        /** @var string $promocode */
        $promocode = $stmt->fetchOne();

        return $this->deserializer->deserialize($promocode);
    }
}
