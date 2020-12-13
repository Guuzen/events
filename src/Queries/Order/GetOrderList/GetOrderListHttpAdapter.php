<?php

declare(strict_types=1);

namespace App\Queries\Order\GetOrderList;

use App\Infrastructure\Http\AppController\AppController;
use App\Order\Query\GetOrderListHandler;
use App\Queries\Order\GetOrderList\GetOrderListRequest;
use App\Queries\Order\OrderResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetOrderListHttpAdapter extends AppController
{
    private $getOrderList;

    public function __construct(GetOrderListHandler $getOrderList)
    {
        $this->getOrderList = $getOrderList;
    }

    /**
     * @Route("/admin/order", methods={"GET"})
     */
    public function __invoke(GetOrderListRequest $request): Response
    {
        $orders = $this->getOrderList->execute($request->eventId);

        return $this->responseJoinedCollection($orders, OrderResource::schema(), OrderResource::class);
    }
}
