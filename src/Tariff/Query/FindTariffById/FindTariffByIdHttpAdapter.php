<?php

declare(strict_types=1);

namespace App\Tariff\Query\FindTariffById;

use App\Infrastructure\Http\AppController;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class FindTariffByIdHttpAdapter extends AppController
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @Route("/admin/tariff/show", methods={"GET"})
     */
    public function __invoke(FindTariffByIdRequest $request): Response
    {
        $stmt = $this->connection->prepare('
            select
                row_to_json(tariff) as json
            from (
                select
                    tariff.id,
                    tariff_details.tariff_type as "tariffType",
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
        $stmt->bindValue('tariff_id', $request->tariffId);
        $stmt->execute();

        /** @psalm-var array{json: string}|false */
        $tariffData = $stmt->fetch();
        if (false === $tariffData) {
            return $this->response(new TariffByIdNotFound());
        }

        /** @var array $json */
        $json   = \json_decode($tariffData['json'], true);
        $tariff = $json;

        return $this->response($tariff);
    }
}
