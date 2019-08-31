<?php

namespace App\Queries\Order;

use App\Infrastructure\Http\AppController;
use App\Queries\Order\FindOrderById\FindOrderById;
use App\Queries\Order\FindOrdersInList\FindOrdersInList;
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
    public function findInList(FindOrdersInList $findOrderInList): Response
    {
        $orders = $this->orderQueries->findInList($findOrderInList->eventId);

        return $this->successJson($orders);
    }

    /**
     * @Route("/show")
     */
    public function findById(FindOrderById $findOrderById): Response
    {
        $order   = $this->orderQueries->findById($findOrderById->orderId);

        return $this->successJson($order);
    }
}
