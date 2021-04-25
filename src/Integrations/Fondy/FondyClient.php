<?php

namespace App\Integrations\Fondy;

use App\Model\Order\OrderId;
use Money\Money;

class FondyClient
{
    public function checkoutUrl(Money $sum, OrderId $orderId): string
    {
        // TODO check for external call missing ?
        return 'http://fondy.checkout.url';
    }
}
