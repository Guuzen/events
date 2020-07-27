<?php

declare(strict_types=1);

namespace App\Tariff\Query\GetTariffList;

use App\Infrastructure\Http\AppController\AppController;
use App\Tariff\ViewModel\TariffList;
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
                json_build_object(
                    \'tariffs\', json_agg(tariff)
                ) as json
            from (
                select
                    *
                from
                    tariff
                where tariff.event_id = :event_id
            ) as tariff
        ');
        $stmt->bindValue('event_id', $request->eventId);
        $stmt->execute();

        /** @psalm-var array{json: string} $tariffsData */
        $tariffsData = $stmt->fetchAll()[0];

        $tariffs = $this->deserializeToViewModel($tariffsData['json'], TariffList::class);

        return $this->response($tariffs);
    }
}
