<?php

declare(strict_types=1);

namespace App\Tariff\AdminApi\GetTariffList;

use App\Infrastructure\Http\AppController\AppController;
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
        $stmt = $this->connection->prepare(
            '
            select
                json_agg(tariff)
            from (
                select
                    *
                from
                    tariff
                where tariff.event_id = :event_id
            ) as tariff
        '
        );
        $stmt->bindValue('event_id', $request->eventId);
        $stmt->execute();

        /** @var string|false $tariffsData */
        $tariffsData = $stmt->fetchOne();
        if ($tariffsData === false) {
            throw new TariffListNotFound('');
        }

        $decoded = $this->deserializeFromDb($tariffsData);

        return $this->response($decoded);
    }
}
