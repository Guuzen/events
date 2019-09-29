<?php

namespace App\Fondy;

use App\Common\Error;
use App\Order\Model\OrderId;
use Money\Money;

class FondyGateway
{
    /**
     * @return string|CanNotGetPaymentUrl
     */
    public function checkoutUrl(Money $sum, OrderId $orderId)
    {
        return 'http://fondy.checkout.url';
    }
}
