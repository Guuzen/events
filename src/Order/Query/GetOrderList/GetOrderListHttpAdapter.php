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
                json_agg(orders)
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

        /** @var string|false $ordersData */
        $ordersData = $stmt->fetchColumn();
        if ($ordersData === false) {
            throw new OrderListNotFound('');
        }

        $decoded = $this->deserializeFromDb($ordersData);

        return $this->response($decoded);
    }

}
