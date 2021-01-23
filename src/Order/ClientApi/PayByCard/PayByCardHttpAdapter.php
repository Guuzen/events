<?php

declare(strict_types=1);

namespace App\Order\ClientApi\PayByCard;

use App\Event\Model\EventId;
use App\Fondy\Fondy;
use App\Infrastructure\Http\AppController\AppController;
use App\Order\Model\OrderId;
use App\Order\Model\Orders;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PayByCardHttpAdapter extends AppController
{
    private $orders;

    private $fondy;

    public function __construct(Orders $orders, Fondy $fondy)
    {
        $this->orders = $orders;
        $this->fondy  = $fondy;
    }

    /**
     * @Route("/order/{orderId}/payByCard", methods={"POST"})
     */
    public function payByCard(PayByCardRequest $request, EventId $eventId): Response
    {
        $order = $this->orders->getById(new OrderId($request->orderId), $eventId);

        $paymentUrl = $order->createFondyPayment($this->fondy);

        return $this->response($paymentUrl);
    }
}
