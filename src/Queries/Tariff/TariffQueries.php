<?php

namespace App\Queries\Tariff;

use App\Queries\Tariff\FindTariffById\TariffById;
use App\Queries\Tariff\FindTariffsInList\TariffInList;
use Doctrine\DBAL\Connection;

final class TariffQueries
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    // TODO stop doing formatting in db ?

    /**
     * @return TariffInList[]
     */
    public function findInList(string $eventId): array
    {
        $stmt = $this->connection->prepare('
            select
                row_to_json(tariff) as json
            from (
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
            ) as tariff
        ');
        $stmt->bindValue('event_id', $eventId);
        $stmt->execute();

        $tariffs = [];
        /** @psalm-var array{json: string} $tariffData */
        foreach ($stmt->fetchAll() as $tariffData) {
            /** @var TariffInList */
            $tariff    = $this->connection->convertToPHPValue($tariffData['json'], TariffInList::class);
            $tariffs[] = $tariff;
        }

        return $tariffs;
    }

    public function findById(string $tariffId): TariffById
    {
        $stmt = $this->connection->prepare('
            select
                row_to_json(tariff) as json
            from (
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
            ) as tariff
        ');
        $stmt->bindValue('tariff_id', $tariffId);
        $stmt->execute();

        /** @psalm-var array{json: string} */
        $tariffData = $stmt->fetch();

        /** @var TariffById */
        $tariff = $this->connection->convertToPHPValue($tariffData['json'], TariffById::class);

        return $tariff;
    }
}
