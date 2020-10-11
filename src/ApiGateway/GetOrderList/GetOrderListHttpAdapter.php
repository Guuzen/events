<?php

declare(strict_types=1);

namespace App\ApiGateway\GetOrderList;

use App\Event\Model\EventId;
use App\Infrastructure\Http\AppController\AppController;
use App\Order\Query\GetOrderList\GetOrderList;
use App\Order\Query\GetOrderList\GetOrderListHandler;
use App\Promocode\Model\PromocodeId;
use App\Promocode\Query\ApplyDiscountHandler\ApplyDiscount;
use App\Promocode\Query\ApplyDiscountHandler\ApplyDiscountHandler;
use App\Promocode\Query\GetPromocodeById\GetPromocodeById;
use App\Promocode\Query\GetPromocodeById\GetPromocodeByIdHandler;
use Money\Currency;
use Money\Money;
use Symfony\Component\Routing\Annotation\Route;

final class GetOrderListHttpAdapter extends AppController
{
    private $getOrderList;

    private $applyDiscount;

    private $getPromocodeById;

    public function __construct(
        GetOrderListHandler $getOrderList,
        ApplyDiscountHandler $applyDiscount,
        GetPromocodeByIdHandler $getPromocodeById
    )
    {
        $this->getOrderList     = $getOrderList;
        $this->applyDiscount    = $applyDiscount;
        $this->getPromocodeById = $getPromocodeById;
    }

    /**
     * @Route("/admin/order", methods={"GET"})
     */
    public function __invoke(GetOrderListRequest $request)
    {
        $orders = $this->getOrderList->execute(
            new GetOrderList(new EventId($request->eventId))
        );

        foreach ($orders as &$order) {
            if ($order['promocodeId'] === null) {
                $order['total']    = new Money($order['price']['amount'], new Currency($order['price']['currency']));
                $order['discount'] = null;
            } else { // TODO infrastructure code ? Move to some join functions ?
                $promocodeId       = new PromocodeId($order['promocodeId']);
                $order['total']    = $this->applyDiscount->execute(
                    new ApplyDiscount(
                        $promocodeId,
                        new Money($order['price']['amount'], new Currency($order['price']['currency']))
                    )
                );
                $promocode         = $this->getPromocodeById->execute(new GetPromocodeById($promocodeId));
                $order['discount'] = $promocode['discount'];
            }
        }

        return $this->response($orders);
    }
}
