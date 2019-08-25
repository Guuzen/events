<?php

namespace App\Queries\Tariff;

use Doctrine\DBAL\Connection;

final class TariffQueries
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    // TODO stop doing formatting in db ?
    public function findEventTariffs(string $eventId): array
    {
        $stmt = $this->connection->prepare('
            select
                id,
                product_type ->> \'type\' as "product_type",
                concat(
                    json_array_elements(price_net -> \'segments\') -> \'price\' ->> \'amount\',
                    \' \',
                    json_array_elements(price_net -> \'segments\') -> \'price\' -> \'currency\' ->> \'code\'
                ) as price,
                (json_array_elements(price_net -> \'segments\') -> \'term\' ->> \'start\' ) as "term_start",
                (json_array_elements(price_net -> \'segments\') -> \'term\' ->> \'end\') as "term_end"
            from
                tariff
            where
                tariff.event_id = :event_id
        ');
        $stmt->bindValue('event_id', $eventId);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById(string $tariffId): array
    {
        $stmt = $this->connection->prepare('
            select
                id,
                product_type ->> \'type\' as "product_type",
                concat(
                    json_array_elements(price_net -> \'segments\') -> \'price\' ->> \'amount\',
                    \' \',
                    json_array_elements(price_net -> \'segments\') -> \'price\' -> \'currency\' ->> \'code\'
                ) as price,
                (json_array_elements(price_net -> \'segments\') -> \'term\' ->> \'start\' ) as "term_start",
                (json_array_elements(price_net -> \'segments\') -> \'term\' ->> \'end\') as "term_end"
            from
                tariff
            where
                tariff.id = :tariff_id
        ');
        $stmt->bindValue('tariff_id', $tariffId);
        $stmt->execute();

        /** @var array */
        return $stmt->fetch();
    }
}
