<?php

declare(strict_types=1);

namespace App\Order\Query\GetOrderById;

use App\Infrastructure\Http\AppController\AppController;
use App\Order\Query\OrderResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetOrderByIdHttpAdapter extends AppController
{
    private $findOrderById;

    public function __construct(GetOrderByIdHandler $findOrderById)
    {
        $this->findOrderById = $findOrderById;
    }

    /**
     * @Route("/admin/order/{orderId}", methods={"GET"})
     */
    public function __invoke(GetOrderByIdRequest $request): Response
    {
        $order = $this->findOrderById->execute($request->orderId);

        return $this->responseJoinedOne($order, OrderResource::schema(), OrderResource::class);
    }
}
