<?php

declare(strict_types=1);

namespace App\Tariff\AdminApi\FindTariffById;

use App\Infrastructure\Http\AppController\AppController;
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
     * @Route("/admin/tariff/{tariffId}", methods={"GET"})
     */
    public function __invoke(FindTariffByIdRequest $request): Response
    {
        $stmt = $this->connection->prepare(
            '
            select
                row_to_json(tariff)
            from (
                select
                    *
                from
                    tariff
                where
                    tariff.id = :tariff_id
            ) as tariff
        '
        );
        $stmt->bindValue('tariff_id', $request->tariffId);
        $stmt->execute();

        /** @var string|false $tariffData */
        $tariffData = $stmt->fetchOne();
        if (false === $tariffData) {
            throw new TariffByIdNotFound('');
        }

        $decoded = $this->deserializeFromDb($tariffData);

        return $this->validateResponse($decoded);
    }
}
