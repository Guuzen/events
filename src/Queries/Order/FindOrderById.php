<?php

namespace App\Queries\Order;

use App\Common\AppRequest;

final class FindOrderById implements AppRequest
{
    public $orderId;

    public function __construct(string $orderId)
    {
        $this->orderId = $orderId;
    }
}
