<?php

declare(strict_types=1);

namespace App\Promocode\Query\GetPromocodeList;

use App\Infrastructure\Persistence\DatabaseSerializer\DatabaseSerializer;
use Doctrine\DBAL\Connection;

final class GetPromocodeListHandler
{
    private $connection;

    private $databaseSerializer;

    public function __construct(Connection $connection, DatabaseSerializer $databaseSerializer)
    {
        $this->connection         = $connection;
        $this->databaseSerializer = $databaseSerializer;
    }

    /**
     * @psalm-suppress MixedReturnTypeCoercion
     *
     * @return array<int, array>
     */
    public function handle(string $eventId): array
    {
        $stmt = $this->connection->prepare(
            '
            select
                json_agg(promocodes)
            from (                
                select
                    *        
                from
                    promocode
                where
                    event_id = :event_id
            ) as promocodes
        '
        );
        $stmt->bindValue('event_id', $eventId);
        $stmt->execute();

        /** @var string|false $promocodesData */
        $promocodesData = $stmt->fetchOne();
        if ($promocodesData === false) {
            throw new PromocodeListNotFound('');
        }

        return $this->databaseSerializer->decode($promocodesData);
    }
}
