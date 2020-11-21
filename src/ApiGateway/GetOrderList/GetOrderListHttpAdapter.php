<?php

declare(strict_types=1);

namespace App\ApiGateway\GetOrderList;

use App\ApiGateway\Responses\Order;
use App\Event\Model\EventId;
use App\Infrastructure\Http\AppController\AppController;
use App\Order\Query\GetOrderList\GetOrderList;
use App\Order\Query\GetOrderList\GetOrderListHandler;
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
        $orders = $this->getOrderList->execute(
            new GetOrderList(new EventId($request->eventId))
        );

        return $this->responseJoinedCollection($orders, Order::schema());
    }
}
