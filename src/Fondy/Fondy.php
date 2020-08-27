<?php

namespace App\Fondy;

use App\Order\Model\OrderId;
use Money\Money;

class Fondy
{
    public function checkoutUrl(Money $sum, OrderId $orderId): string
    {
        // TODO check for external call missing ?
        return 'http://fondy.checkout.url';
    }
}
