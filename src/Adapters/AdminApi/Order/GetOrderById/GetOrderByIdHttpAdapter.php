<?php

declare(strict_types=1);

namespace App\Adapters\AdminApi\Order\GetOrderById;

use App\Infrastructure\Http\AppController\AppController;
use App\Infrastructure\Persistence\ResultSetMapping;
use Doctrine\DBAL\Connection;
use Guuzen\ResourceComposer\ResourceComposer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetOrderByIdHttpAdapter extends AppController
{
    private $connection;

    private $composer;

    public function __construct(Connection $connection, ResourceComposer $composer)
    {
        $this->connection = $connection;
        $this->composer   = $composer;
    }

    /**
     * @Route("/admin/order/{orderId}", methods={"GET"})
     */
    public function __invoke(GetOrderByIdRequest $request): Response
    {
        /** @var \Doctrine\DBAL\Driver\PDO\Connection $pdo */
        $pdo = $this->connection->getWrappedConnection();

        $stmt = $pdo->prepare(
            '
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
                ticket_order.id = :order_id
            '
        );
        $stmt->bindValue('order_id', $request->orderId);
        $stmt->execute();

        /** @var array $order */
        $order = $stmt->fetch(\PDO::FETCH_ASSOC);

        $mapping = ResultSetMapping::forStatement($stmt);
        $order   = $mapping->mapKnownColumns($this->connection->getDatabasePlatform(), $order);

        $order = $this->composer->composeOne($order, 'order');

        return $this->response($order);
    }
}
