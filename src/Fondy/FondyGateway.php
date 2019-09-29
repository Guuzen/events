<?php

namespace App\Fondy;

use App\Common\Error;
use App\Order\Model\OrderId;
use Money\Money;

class FondyGateway
{
    /**
     * @return string
     */
    public function checkoutUrl(Money $sum, OrderId $orderId)
    {
        return 'http://fondy.checkout.url';
    }
}
