<?php

namespace App\Order\Action;

use App\Infrastructure\Http\AppController;
use App\Common\Error;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class OrderHttpAdapter extends AppController
{
    private $handler;

    public function __construct(OrderHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @Route("/order/place", methods={"POST"})
     */
    public function placeOrder(PlaceOrder $placeOrder): Response
    {
        $orderId = $this->handler->placeOrder($placeOrder);

        if ($orderId instanceof Error) {
            return $this->errorJson($orderId);
        }

        $response = [
            'id'          => (string) $orderId,
        ];

        return $this->successJson($response);
    }

    /**
     * @Route("/admin/order/markPaid", methods={"POST"})
     */
    public function markOrderPaid(MarkOrderPaid $markOrderPaid): Response
    {
        $result = $this->handler->markOrderPaid($markOrderPaid);
        if ($result instanceof Error) {
            return $this->errorJson($result);
        }

        return $this->successJson();
    }
}
