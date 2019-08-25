<?php

namespace App\Queries\Order;

use App\Common\AppController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/order", methods={"GET"})
 */
final class OrderQueriesHttpAdapter extends AppController
{
    private $orderQueries;

    public function __construct(OrderQueries $orderQueries)
    {
        $this->orderQueries = $orderQueries;
    }

    /**
     * @Route("/list")
     */
    public function findEventOrders(FindEventOrders $findEventOrders): Response
    {
        $orders = $this->orderQueries->findEventOrders($findEventOrders->eventId);

        return $this->successJson($orders);
    }

    /**
     * @Route("/show")
     */
    public function findOrderById(FindOrderById $findOrderById): Response
    {
        $order   = $this->orderQueries->findOrderById($findOrderById->orderId);

        return $this->successJson($order);
    }
}
