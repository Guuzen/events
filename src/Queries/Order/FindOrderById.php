<?php

namespace App\Queries\Order;

use App\Common\AppRequest;

final class FindOrderById implements AppRequest
{
    /**
     * @readonly
     */
    public $orderId;

    public function __construct(string $orderId)
    {
        $this->orderId = $orderId;
    }
}
