<?php

declare(strict_types=1);

namespace App\ApiGateway\GetOrderList;

use App\Event\Model\EventId;
use App\Infrastructure\Http\AppController\AppController;
use App\Infrastructure\JoinResponse\JoinResponse;
use App\Order\Query\GetOrderList\GetOrderList;
use App\Order\Query\GetOrderList\GetOrderListHandler;
use App\Order\Query\OrderResource;
use App\Promocode\Query\GetPromocodesByIds\GetPromocodesByIds;
use App\Promocode\Query\GetPromocodesByIds\GetPromocodesByIdsHandler;
use Symfony\Component\Routing\Annotation\Route;

final class GetOrderListHttpAdapter extends AppController
{
    private $getOrderList;

    private $getPromocodesByIds;

    private $joinResponse;

    public function __construct(
        GetOrderListHandler $getOrderList,
        GetPromocodesByIdsHandler $getPromocodesByIds,
        JoinResponse $joinResponse
    )
    {
        $this->getOrderList       = $getOrderList;
        $this->getPromocodesByIds = $getPromocodesByIds;
        $this->joinResponse       = $joinResponse;
    }

    /**
     * @Route("/admin/order", methods={"GET"})
     */
    public function __invoke(GetOrderListRequest $request)
    {
        $orders = $this->getOrderList->execute(
            new GetOrderList(new EventId($request->eventId))
        );

        $promocodes = $this->getPromocodesByIds->execute(
            new GetPromocodesByIds(
                \array_filter(
                    \array_map(
                        static fn(OrderResource $order) => $order->promocodeId,
                        $orders
                    )
                )
            )
        );

        $response = $this->joinResponse->oneToOne(GetOrderListItemResponse::class, $orders, $promocodes);

        return $this->response($response);
    }
}
