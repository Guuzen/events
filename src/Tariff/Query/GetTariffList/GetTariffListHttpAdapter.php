<?php

declare(strict_types=1);

namespace App\Tariff\Query\GetTariffList;

use App\Infrastructure\Http\AppController;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetTariffListHttpAdapter extends AppController
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @Route("/admin/tariff/list", methods={"GET"})
     */
    public function __invoke(GetTariffListRequest $request): Response
    {
        $stmt = $this->connection->prepare('
            select
                row_to_json(tariff) as json
            from (
                select
                    tariff.event_id as "eventId",
                    tariff.id,
                    segments,
                    tariff.product_type -> \'type\' as "productType"
                from (
                    select
                        id,
                        array_agg(
                            json_build_object(
                                \'price\', json_build_object(
                                    \'amount\', segments -> \'price\' -> \'amount\',
                                    \'currency\', segments -> \'price\' -> \'currency\' -> \'code\'
                                ),
                                \'term\', json_build_object(
                                    \'start\', concat(segments -> \'term\' ->> \'start\', \'Z\'),
                                    \'end\', concat(segments -> \'term\' ->> \'end\', \'Z\')
                                )
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
            ) as tariff
        ');
        $stmt->bindValue('event_id', $request->eventId);
        $stmt->execute();

        $tariffs = [];
        /** @psalm-var array{json: string} $tariffData */
        foreach ($stmt->fetchAll() as $tariffData) {
            /** @var array $json */
            $json      = \json_decode($tariffData['json'], true);
            $tariff    = $json;
            $tariffs[] = $tariff;
        }

        return $this->response($tariffs);
    }
}
