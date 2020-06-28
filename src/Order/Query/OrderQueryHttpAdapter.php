<?php

namespace App\Order\Query;

use App\Infrastructure\Http\AppController;
use App\Order\Query\FindOrderById\FindOrderByIdQuery;
use App\Order\Query\FindOrderById\FindOrderByIdRequest;
use App\Order\Query\GetOrderList\GetOrderListQuery;
use App\Order\Query\GetOrderList\GetOrderListRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class OrderQueryHttpAdapter extends AppController
{
    /**
     * @Route("/admin/order/list")
     */
    public function getOrderList(GetOrderListQuery $query, GetOrderListRequest $request): Response
    {
        $orders = $query($request->eventId);

        return $this->response($orders);
    }

    /**
     * @Route("/admin/order/show")
     */
    public function findOrderById(FindOrderByIdQuery $query, FindOrderByIdRequest $request): Response
    {
        $order = $query($request->orderId);

        return $this->response($order);
    }
}
