<?php

declare(strict_types=1);

namespace App\Order\Query\GetOrderList;

use App\Infrastructure\Http\AppController;
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
     * @Route("/admin/order/list")
     */
    public function __invoke(GetOrderListRequest $request): Response
    {
        $stmt = $this->connection->prepare('
            select
                "order".event_id as "eventId",
                "order".id as id,
                "order".tariff_id as "tariffId",
                "order".user_id as "userId",
                "order".paid as paid,
                "order".cancelled as "cancelled",
                concat("order".maked_at, \'Z\') as "makedAt",
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
                end) as discount
            from
                "order"
            where
                "order".event_id = :event_id
        ');
        $stmt->bindValue('event_id', $request->eventId);
        $stmt->execute();

        $rows   = $stmt->fetchAll();
        $orders = [];

        /** @psalm-var array{sum: string, discount: ?string} $row */
        foreach ($rows as $row) {
            $order = $row;

            /** @var array $sum */
            $sum          = \json_decode($row['sum'], true);
            $order['sum'] = $sum;

            /** @var array|null $discount */
            $discount          = $row['discount'] ? \json_decode($row['discount'], true) : null;
            $order['discount'] = $discount;

            $orders[] = $order;
        }
        // TODO mapping?

        return $this->response($orders);
    }

}