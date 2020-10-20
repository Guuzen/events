<?php

declare(strict_types=1);

namespace App\ApiGateway\GetOrderList;

use App\Event\Model\EventId;
use App\Infrastructure\Http\AppController\AppController;
use App\Infrastructure\JoinResponse\Collection;
use App\Order\Query\GetOrderList\GetOrderList;
use App\Order\Query\GetOrderList\GetOrderListHandler;
use App\Order\Query\OrderResource;
use App\Promocode\Query\GetPromocodesByIds\GetPromocodesByIds;
use App\Promocode\Query\GetPromocodesByIds\GetPromocodesByIdsHandler;
use App\Promocode\Query\PromocodeResource;
use Symfony\Component\Routing\Annotation\Route;

final class GetOrderListHttpAdapter extends AppController
{
    private $getOrderList;

    private $getPromocodesByIds;

    public function __construct(GetOrderListHandler $getOrderList, GetPromocodesByIdsHandler $getPromocodesByIds)
    {
        $this->getOrderList       = $getOrderList;
        $this->getPromocodesByIds = $getPromocodesByIds;
    }

    /**
     * @Route("/admin/order", methods={"GET"})
     */
    public function __invoke(GetOrderListRequest $request)
    {
        $orders              = $this->getOrderList->execute(
            new GetOrderList(new EventId($request->eventId))
        );
        $ordersCollection    = new Collection($orders, fn(OrderResource $order) => $order->promocodeId);
        $promocodeIds        = $ordersCollection->getKeys();
        $promocodes          = $this->getPromocodesByIds->execute(
            new GetPromocodesByIds($promocodeIds)
        );
        $promocodeCollection = new Collection($promocodes, fn(PromocodeResource $promocode) => $promocode->id);

        $response = $ordersCollection->eachToOne(GetOrderListItemResponse::class, $promocodeCollection);

        return $this->response($response);
    }
}
