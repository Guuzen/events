<?php

declare(strict_types=1);

namespace App\Adapters\AdminApi\Tariff\FindTariffById;

use App\Infrastructure\Http\AppController\AppController;
use App\Infrastructure\Persistence\ResultSetMapping;
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
        /** @var \Doctrine\DBAL\Driver\PDO\Connection $pdo */
        $pdo = $this->connection->getWrappedConnection();

        $stmt = $pdo->prepare(
            '
            select
                *
            from
                tariff
            where
                tariff.id = :tariff_id
            '
        );
        $stmt->bindValue('tariff_id', $request->tariffId);
        $stmt->execute();

        /** @var array $tariff */
        $tariff = $stmt->fetch(\PDO::FETCH_ASSOC);

        $mapping = ResultSetMapping::forStatement($stmt);
        $tariff  = $mapping->mapKnownColumns($this->connection->getDatabasePlatform(), $tariff);

        return $this->response($tariff);
    }
}
