<?php

declare(strict_types=1);

namespace App\ApiGateway\FindOrderById;

use App\Infrastructure\Http\AppController\AppController;
use App\Order\Model\OrderId;
use App\Order\Query\FindOrderById\FindOrderById;
use App\Order\Query\FindOrderById\FindOrderByIdHandler;
use App\Promocode\Model\PromocodeId;
use App\Promocode\Query\ApplyDiscountHandler\ApplyDiscountHandler;
use App\Promocode\Query\GetPromocodeById\GetPromocodeById;
use App\Promocode\Query\GetPromocodeById\GetPromocodeByIdHandler;
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

        if ($order->promocodeId === null) {
            $promocode = null;
        } else {
            // TODO is it necessary to use objects here ?
            $promocode = $this->getPromocodeById->execute(
                new GetPromocodeById(new PromocodeId($order->promocodeId))
            );
        }

        return $this->response(new FindOrderByIdResponse($order, $promocode));
    }
}
