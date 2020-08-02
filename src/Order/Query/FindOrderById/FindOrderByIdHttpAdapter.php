<?php

declare(strict_types=1);

namespace App\Order\Query\FindOrderById;

use App\Infrastructure\Http\AppController\AppController;
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
                "order".event_id as "event_id",
                "order".id as id,
                "order".tariff_id as "tariff_id",
                "order".user_id as "user_id",
                "order".paid as paid,
                "order".cancelled as "cancelled",
                concat("order".maked_at, \'Z\') as "maked_at",
                json_build_object(
                    \'amount\', "order".sum ->> \'amount\',
                    \'currency\', "order".sum -> \'currency\' ->> \'code\'
                ) as sum,
                (case
                    when
                        "order".discount is null
                    then
                        null
                    else
                        json_build_object(
                            \'amount\', "order".discount -> \'amount\' ->> \'amount\',
                            \'currency\', "order".discount -> \'amount\' -> \'currency\' ->> \'code\'
                        )
                end) as discount,
                json_build_object(
                    \'amount\', "order".total ->> \'amount\',
                    \'currency\', "order".total -> \'currency\' ->> \'code\'
                ) as total
            from
                "order"
            where
                "order".id = :order_id                
        ');
        $stmt->bindValue('order_id', $request->orderId);
        $stmt->execute();

        /**
         * @psalm-var array{
         *      sum: string,
         *      discount: ?string,
         *      total: string
         * }|false $order
         */
        $order = $stmt->fetch();
        if (false === $order) {
            return $this->response(new OrderNotFound());
        }
        /** @var array $sum */
        $sum          = \json_decode($order['sum'], true);
        $order['sum'] = $sum;

        /** @var array|null $discount */
        $discount          = $order['discount'] ? \json_decode($order['discount'], true) : null;
        $order['discount'] = $discount;

        /** @var array $total */
        $total          = \json_decode($order['total'], true);
        $order['total'] = $total;

        return $this->response($order);
    }
}
