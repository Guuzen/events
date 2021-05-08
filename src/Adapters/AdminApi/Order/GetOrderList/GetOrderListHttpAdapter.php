<?php

declare(strict_types=1);

namespace App\Adapters\AdminApi\Order\GetOrderList;

use App\Infrastructure\Http\AppController\AppController;
use App\Infrastructure\ResComposer\ResourceComposer;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetOrderListHttpAdapter extends AppController
{
    private $connection;

    private $composer;

    public function __construct(Connection $connection, ResourceComposer $composer)
    {
        $this->connection = $connection;
        $this->composer   = $composer;
    }

    /**
     * @Route("/admin/order", methods={"GET"})
     */
    public function __invoke(GetOrderListRequest $request): Response
    {
        $stmt = $this->connection->prepare(
            '
            select
                json_agg(orders)
            from (
                select
                    ticket_order.id,
                    ticket_order.event_id,
                    ticket_order.tariff_id,
                    ticket_order.paid,
                    ticket_order.price,
                    ticket_order.cancelled,
                    ticket_order.user_id,
                    ticket_order.maked_at,
                    ticket_order.tariff_type,
                    ticket_order.total,
                    ticket_order.promocode_id as promocode,
                    \'ticket\' as product_type
                from
                    ticket_order
                where
                    ticket_order.event_id = :event_id
            ) as orders
        '
        );
        $stmt->bindValue('event_id', $request->eventId);
        $stmt->execute();

        /** @var string|false $orders */
        $orders = $stmt->fetchOne();
        if ($orders === false) {
            throw new \RuntimeException('order list not found');
        }

        /** @var array<int, array> $ordersData */
        $ordersData = $this->deserializeFromDb($orders);

        $resources = $this->composer->compose($ordersData, 'order');

        return $this->validateResponse($resources);
    }
}
