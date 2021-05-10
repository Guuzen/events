<?php

declare(strict_types=1);

namespace App\Adapters\AdminApi\Tariff\GetTariffList;

use App\Infrastructure\Http\AppController\AppController;
use App\Infrastructure\Persistence\ResultSetMapping;
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
     * @Route("/admin/tariff", methods={"GET"})
     */
    public function __invoke(GetTariffListRequest $request): Response
    {
        /** @var \Doctrine\DBAL\Driver\PDO\Connection $pdo */
        $pdo = $this->connection->getWrappedConnection();

        $stmt = $pdo->prepare(
            '
            select
                *
            from
                tariff
            where tariff.event_id = :event_id
            '
        );
        $stmt->bindValue('event_id', $request->eventId);
        $stmt->execute();

        /** @var array<int, array> $tariffs */
        $tariffs = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $mapping = ResultSetMapping::forStatement($stmt);
        $tariffs = $mapping->mapKnownColumnsArray($this->connection->getDatabasePlatform(), $tariffs);

        return $this->response($tariffs);
    }
}
