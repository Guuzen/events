<?php

declare(strict_types=1);

namespace App\Order\Query\GetOrderList;

use App\Infrastructure\Http\AppController\AppController;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetOrderListHttpAdapter extends AppController
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @Route("/admin/order", methods={"GET"})
     */
    public function __invoke(GetOrderListRequest $request): Response
    {
        $stmt = $this->connection->prepare('
            select
                json_build_object(
                    \'data\', json_agg(orders)
                ) as json
            from (
                select
                    *
                from
                    "order"
                where
                    "order".event_id = :event_id
            ) as orders
        ');
        $stmt->bindValue('event_id', $request->eventId);
        $stmt->execute();

        /** @psalm-var array{json: string} $ordersData */
        $ordersData = $stmt->fetchAll()[0];

        return $this->toJsonResponse($ordersData['json']);
    }

}
