<?php

namespace App\Queries\Tariff;

use App\Queries\Tariff\FindTariffById\TariffById;
use App\Queries\Tariff\FindTariffById\TariffByIdNotFound;
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
                    tariff.id,
                    tariff_details.tariff_type as tariff_type,
                    segments,
                    tariff.product_type -> \'type\' as product_type
                from (
                    select
                        id,
                        array_agg(
                            json_build_object(
                                \'price\', json_build_object(
                                    \'amount\', segments -> \'price\' -> \'amount\',
                                    \'currency\', segments -> \'price\' -> \'currency\' -> \'code\'
                                ),
                                \'term\', segments -> \'term\'
                            )
                        ) as segments
                    from (
                        select
                            id,
                            json_array_elements(price_net -> \'segments\') as segments
                        from tariff
                        where tariff.event_id = :event_id
                    ) as unwinded_tariff
                    group by id
                ) as formatted_tariff
                left join
                    tariff on tariff.id = formatted_tariff.id
                left join
                    tariff_details on tariff_details.id = tariff.id
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

    /**
     * @return TariffById|TariffByIdNotFound
     */
    public function findById(string $tariffId)
    {
        $stmt = $this->connection->prepare('
            select
                row_to_json(tariff) as json
            from (
                select
                    tariff.id,
                    tariff_details.tariff_type as tariff_type,
                    segments,
                    tariff.product_type -> \'type\' as product_type
                from (
                    select
                        id,
                        array_agg(
                            json_build_object(
                                \'price\', json_build_object(
                                    \'amount\', segments -> \'price\' -> \'amount\',
                                    \'currency\', segments -> \'price\' -> \'currency\' -> \'code\'
                                ),
                                \'term\', segments -> \'term\'
                            )
                        ) as segments
                    from (
                        select
                            id,
                            json_array_elements(price_net -> \'segments\') as segments
                        from tariff
                        where tariff.id = :tariff_id
                    ) as unwinded_tariff
                    group by id
                ) as formatted_tariff
                left join
                    tariff on tariff.id = formatted_tariff.id
                left join
                    tariff_details on tariff_details.id = tariff.id
            ) as tariff
        ');
        $stmt->bindValue('tariff_id', $tariffId);
        $stmt->execute();

        /** @psalm-var array{json: string}|false */
        $tariffData = $stmt->fetch();
        if (false === $tariffData) {
            return new TariffByIdNotFound();
        }

        /** @var TariffById */
        $tariff = $this->connection->convertToPHPValue($tariffData['json'], TariffById::class);

        return $tariff;
    }
}
