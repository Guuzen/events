<?php

declare(strict_types=1);

namespace App\ApiGateway\FindOrderById;

use App\ApiGateway\Responses\OrderResponse;
use App\Infrastructure\Http\AppController\AppController;
use App\Order\Model\OrderId;
use App\Order\Query\FindOrderById\FindOrderById;
use App\Order\Query\FindOrderById\FindOrderByIdHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class FindOrderByIdHttpAdapter extends AppController
{
    private $findOrderById;

    public function __construct(FindOrderByIdHandler $findOrderById)
    {
        $this->findOrderById = $findOrderById;
    }

    /**
     * @Route("/admin/order/{orderId}", methods={"GET"})
     */
    public function __invoke(FindOrderByIdRequest $request): Response
    {
        $order = $this->findOrderById->execute(
            new FindOrderById(new OrderId($request->orderId))
        );

        return $this->responseJoinedOne($order, OrderResponse::schema());
    }
}
