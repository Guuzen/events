<?php

namespace App\Queries\FindTariffs;

use Doctrine\DBAL\Connection;

final class FindTariffs
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    // TODO stop doing formatting in db
    public function __invoke(): array
    {
        $stmt = $this->connection->query('
            select
                id,
                product_type ->> \'type\' as "productType",
                concat(
                    json_array_elements(price_net -> \'segments\') -> \'price\' ->> \'amount\',
                    \' \',
                    json_array_elements(price_net -> \'segments\') -> \'price\' -> \'currency\' ->> \'code\'
                ) as price,
                (json_array_elements(price_net -> \'segments\') -> \'term\' ->> \'start\' ) as "termStart",
                (json_array_elements(price_net -> \'segments\') -> \'term\' ->> \'end\') as "termEnd"
            from
                tariff
        ');

        return $stmt->fetchAll();
    }
}
