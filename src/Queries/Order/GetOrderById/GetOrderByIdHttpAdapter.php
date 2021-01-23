<?php

declare(strict_types=1);

namespace App\Queries\Order\GetOrderById;

use App\Infrastructure\Http\AppController\AppController;
use App\Infrastructure\ResComposer\ResourceComposer;
use App\Order\Query\GetOrderByIdHandler;
use App\Queries\Order\OrderResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetOrderByIdHttpAdapter extends AppController
{
    private $findOrderById;

    private $composer;

    public function __construct(GetOrderByIdHandler $findOrderById, ResourceComposer $composer)
    {
        $this->findOrderById = $findOrderById;
        $this->composer      = $composer;
    }

    /**
     * @Route("/admin/order/{orderId}", methods={"GET"})
     */
    public function __invoke(GetOrderByIdRequest $request): Response
    {
        $order = $this->findOrderById->execute($request->orderId);

        $resource = $this->composer->composeOne($order, OrderResource::class);

        return $this->response($resource);
    }
}
