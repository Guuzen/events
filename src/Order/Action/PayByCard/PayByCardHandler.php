<?php

declare(strict_types=1);

namespace App\Order\Action\PayByCard;

use App\Fondy\Fondy;
use App\Order\Model\Orders;

final class PayByCardHandler
{
    private $orders;

    private $fondy;

    public function __construct(Orders $orders, Fondy $fondy)
    {
        $this->orders = $orders;
        $this->fondy  = $fondy;
    }

    public function payByCard(PayByCard $payByCard): string
    {
        $order = $this->orders->getById($payByCard->orderId, $payByCard->eventId);

        return $order->createFondyPayment($this->fondy);
    }
}
