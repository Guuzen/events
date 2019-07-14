<?php

declare(strict_types=1);

namespace App\Queries;

use App\Common\AppController;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class FindOrders extends AppController
{
    /**
     * @Route("/admin/orders")
     */
    public function all(Request $request): Response
    {
        /** @var Connection $connection */
        $connection = $this->getDoctrine()->getConnection();
        $stmt       = $connection->query('
            select
                "order".id as id,
                ticket.status as status,
                "user".id as user_id,
                "order".paid as paid,
                "order".maked_at as maked_at
            from
                "order"
            left join ticket_tariff on "order".tariff_id = ticket_tariff.id
            left join "user" on "order".user_id = "user".id
            left join ticket on "order".product_id = ticket.id
        ');

        $orders = $stmt->fetchAll();

        return $this->successJson($orders);
    }
}
