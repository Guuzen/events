<?php

declare(strict_types=1);

namespace App\Tariff\Query\FindTariffById;

use App\Infrastructure\Http\AppController;
use App\Tariff\Query\Tariff;
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
                    *
                from
                    tariff
                where
                    tariff.id = :tariff_id
            ) as tariff
        ');
        $stmt->bindValue('tariff_id', $request->tariffId);
        $stmt->execute();

        /** @psalm-var array{json: string}|false */
        $tariffData = $stmt->fetch();
        if (false === $tariffData) {
            return $this->response(new TariffByIdNotFound());
        }

        $tariff = $this->deserializeFromDb($tariffData['json'], Tariff::class);

        return $this->response($tariff);
    }
}
