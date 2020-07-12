<?php

declare(strict_types=1);

namespace App\Order\Query\FindOrderById;

use App\Infrastructure\Http\AppController;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class FindOrderByIdHttpAdapter extends AppController
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @Route("/admin/order/show")
     */
    public function __invoke(FindOrderByIdRequest $request): Response
    {
        $stmt = $this->connection->prepare('
            select
                "order".event_id as "eventId",
                "order".id as id,
                "order".tariff_id as "tariffId",
                "order".user_id as "userId",
                "order".paid as paid,
                "order".cancelled as "cancelled",
                "order".maked_at as "makedAt",
                json_build_object(
                    \'amount\', "order".sum ->> \'amount\',
                    \'currency\', "order".sum -> \'currency\' ->> \'code\'
                ) as sum,
                "order".discount -> \'amount\' ->> \'amount\' as discount
            from
                "order"
            where
                "order".id = :order_id                
        ');
        $stmt->bindValue('order_id', $request->orderId);
        $stmt->execute();

        /** @psalm-var array{sum: string}|false $order */
        $order = $stmt->fetch();
        if (false === $order) {
            return $this->response(new OrderNotFound());
        }
        /** @var array $sum */
        $sum          = \json_decode($order['sum'], true);
        $order['sum'] = $sum;

        return $this->response($order);
    }
}
