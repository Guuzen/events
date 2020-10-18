<?php

declare(strict_types=1);

namespace App\Promocode\Query\GetPromocodeById;

use App\Infrastructure\Persistence\DatabaseSerializer\DatabaseSerializer;
use App\Promocode\Query\PromocodeResource;
use Doctrine\DBAL\Connection;

final class GetPromocodeByIdHandler
{
    private $connection;

    private $serializer;

    public function __construct(Connection $connection, DatabaseSerializer $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    public function execute(GetPromocodeById $query): PromocodeResource
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

        return $this->serializer->deserialize($promocode, PromocodeResource::class);
    }
}
