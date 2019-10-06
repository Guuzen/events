<?php

namespace App\Fondy;

use App\Order\Model\OrderId;
use Money\Money;

class Fondy
{
    /**
     * @return string|CantGetPaymentUrl
     */
    public function checkoutUrl(Money $sum, OrderId $orderId)
    {
        return 'http://fondy.checkout.url';
    }
}