<?php

declare(strict_types=1);

namespace App\Promocode\Query\GetPromocodesByIds;

use App\Infrastructure\Persistence\DatabaseSerializer\DatabaseSerializer;
use App\Promocode\Query\PromocodeResource;
use Doctrine\DBAL\Connection;

final class GetPromocodesByIdsHandler
{
    private $connection;

    private $serializer;

    public function __construct(Connection $connection, DatabaseSerializer $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    public function execute(GetPromocodesByIds $query): array
    {
        /** @var string|null $promocodes */
        $promocodes = $this->connection->fetchOne(
            '
            select
                json_agg(promocode)
            from (
                select
                    *
                from
                    promocode
                where
                    promocode.id in (:promocodes_ids)                 
            ) as promocode
            ',
            ['promocodes_ids' => $query->promocodesIds],
            ['promocodes_ids' => Connection::PARAM_STR_ARRAY],
        );

        if ($promocodes === null) {
            return [];
        }

        return $this->serializer->deserializeToArray($promocodes, PromocodeResource::class);
    }
}
