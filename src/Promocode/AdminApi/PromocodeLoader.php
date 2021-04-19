<?php

declare(strict_types=1);

namespace App\Promocode\AdminApi;

use App\Infrastructure\Persistence\DatabaseSerializer\DatabaseSerializer;
use App\Infrastructure\ResComposer\ResourceDataLoader;
use Doctrine\DBAL\Connection;

final class PromocodeLoader implements ResourceDataLoader
{
    private $connection;

    private $serializer;

    public function __construct(Connection $connection, DatabaseSerializer $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * @psalm-suppress MixedReturnTypeCoercion
     */
    public function load(array $promocodeIds): array
    {
        /** @var string|null $promocodes */
        $promocodes = $this->connection->fetchOne(
            '
            select
                json_agg(promocode)
            from (
                select
                    id, discount
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

        return $this->serializer->decode($promocodes);
    }
}
