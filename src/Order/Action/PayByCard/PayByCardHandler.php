<?php

declare(strict_types=1);

namespace App\Order\Action\PayByCard;

use App\Common\Error;
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

    /**
     * @return string|Error
     */
    public function payByCard(PayByCard $payByCard)
    {
        $order = $this->orders->findById($payByCard->orderId, $payByCard->eventId);
        if ($order instanceof Error) {
            return $order;
        }

        return $order->createFondyPayment($this->fondy);
    }
}
