<?php

namespace App\Queries\Promocode;

use App\Queries\Promocode\FindPromocodesInList\PromocodeInList;
use Doctrine\DBAL\Connection;

final class PromocodeQueries
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return PromocodeInList[]
     */
    public function findInList(string $eventId): array
    {
        $stmt = $this->connection->prepare('
            select
                row_to_json(promocode) as json
            from (
                select
                    id,
                    event_id,
                    code,
                    json_build_object(
                        \'amount\', discount -> \'amount\' -> \'amount\',
                        \'currency\', discount -> \'amount\' -> \'currency\' -> \'code\',
                        \'type\', discount -> \'type\'
                    ) as discount,
                    use_limit,
                    expire_at,
                    usable,
                    used_in_orders,
                    allowed_tariffs
                from
                    regular_promocode
                where
                    event_id = :event_id                 
            ) as promocode
        ');
        $stmt->bindValue('event_id', $eventId);
        $stmt->execute();

        $promocodes = [];
        /** @psalm-var array{json: string} $promocodeData */
        foreach ($stmt->fetchAll() as $promocodeData) {
            /** @var PromocodeInList */
            $promocode    = $this->connection->convertToPHPValue($promocodeData['json'], PromocodeInList::class);
            $promocodes[] = $promocode;
        }

        return $promocodes;
    }
}
