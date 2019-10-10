<?php

namespace App\Order\Action;

use App\Infrastructure\Http\AppController;
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

        return $this->response($orderId);
    }

    /**
     * @Route("/admin/order/markPaid", methods={"POST"})
     */
    public function markOrderPaid(MarkOrderPaid $markOrderPaid): Response
    {
        $result = $this->handler->markOrderPaid($markOrderPaid);

        return $this->response($result);
    }

    /**
     * @Route("/order/payByCard", methods={"POST"})
     */
    public function payByCard(PayByCard $payByCard): Response
    {
        $paymentUrl = $this->handler->payByCard($payByCard);

        return $this->response($paymentUrl);
    }

    /**
     * @Route("/order/markPaidByFondy", methods={"POST"})
     */
    public function markOrdePaidByFondy(MarkOrderPaidByFondy $markOrderPaidByFondy): Response
    {
        $result = $this->handler->markOrderPaidByFondy($markOrderPaidByFondy);

        return $this->response($result);
    }
}
