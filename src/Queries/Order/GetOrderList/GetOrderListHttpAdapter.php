<?php

declare(strict_types=1);

namespace App\Queries\Order\GetOrderList;

use App\Infrastructure\Http\AppController\AppController;
use App\Infrastructure\ResComposer\ResourceComposer;
use App\Order\Query\GetOrderListHandler;
use App\Queries\Order\OrderResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetOrderListHttpAdapter extends AppController
{
    private $getOrderList;

    private $composer;

    public function __construct(GetOrderListHandler $getOrderList, ResourceComposer $composer)
    {
        $this->getOrderList = $getOrderList;
        $this->composer     = $composer;
    }

    /**
     * @Route("/admin/order", methods={"GET"})
     */
    public function __invoke(GetOrderListRequest $request): Response
    {
        $orders = $this->getOrderList->execute($request->eventId);

        $resources = $this->composer->compose($orders, OrderResource::class);

        return $this->response($resources);
    }
}
