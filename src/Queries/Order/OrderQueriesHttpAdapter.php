<?php

namespace App\Queries\Order;

use App\Common\AppController;
use Symfony\Component\HttpFoundation\Request;
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
    public function findAll(Request $request): Response
    {
        $orders = $this->orderQueries->findAll((string) $request->get('event_id'));

        return $this->successJson($orders);
    }

    /**
     * @Route("/show")
     */
    public function findById(Request $request): Response
    {
        $orderId = (string) $request->get('order_id');
        $order   = $this->orderQueries->findById($orderId);

        return $this->successJson($order);
    }
}
