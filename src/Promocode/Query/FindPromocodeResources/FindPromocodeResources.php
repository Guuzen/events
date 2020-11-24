<?php

declare(strict_types=1);

namespace App\Promocode\Query\FindPromocodeResources;

use App\Infrastructure\Persistence\DatabaseSerializer\DatabaseSerializer;
use App\Infrastructure\ResponseComposer\ResourceProvider;
use App\Promocode\Query\PromocodeResource;
use Doctrine\DBAL\Connection;

final class FindPromocodeResources implements ResourceProvider
{
    private $connection;

    private $serializer;

    public function __construct(Connection $connection, DatabaseSerializer $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * @return PromocodeResource[]
     */
    public function resources(array $promocodeIds): array
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
            ['promocodes_ids' => $promocodeIds],
            ['promocodes_ids' => Connection::PARAM_STR_ARRAY],
        );

        if ($promocodes === null) {
            return [];
        }

        return $this->serializer->deserializeToArray($promocodes, PromocodeResource::class);
    }
}
