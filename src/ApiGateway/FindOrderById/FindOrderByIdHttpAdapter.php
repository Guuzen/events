<?php

declare(strict_types=1);

namespace App\ApiGateway\FindOrderById;

use App\Infrastructure\Http\AppController\AppController;
use App\Order\Model\OrderId;
use App\Order\Query\FindOrderById\FindOrderById;
use App\Order\Query\FindOrderById\FindOrderByIdHandler;
use App\Promocode\Model\PromocodeId;
use App\Promocode\Query\ApplyDiscountHandler\ApplyDiscount;
use App\Promocode\Query\ApplyDiscountHandler\ApplyDiscountHandler;
use App\Promocode\Query\GetPromocodeById\GetPromocodeById;
use App\Promocode\Query\GetPromocodeById\GetPromocodeByIdHandler;
use Money\Currency;
use Money\Money;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class FindOrderByIdHttpAdapter extends AppController
{
    private $findOrderById;

    private $applyDiscount;

    private $getPromocodeById;

    public function __construct(
        FindOrderByIdHandler $findOrderById,
        ApplyDiscountHandler $applyDiscount,
        GetPromocodeByIdHandler $getPromocodeById
    )
    {
        $this->findOrderById    = $findOrderById;
        $this->applyDiscount    = $applyDiscount;
        $this->getPromocodeById = $getPromocodeById;
    }

    /**
     * @Route("/admin/order/{orderId}", methods={"GET"})
     */
    public function __invoke(FindOrderByIdRequest $request): Response
    {
        $order = $this->findOrderById->execute(
            new FindOrderById(new OrderId($request->orderId))
        );
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

        return $this->response($order);
    }
}
