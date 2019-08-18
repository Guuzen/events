<?php

namespace App\Order\Action;

use App\Common\AppRequest;

final class MarkOrderPaid implements AppRequest
{
    public $orderId;

    public function __construct(string $orderId)
    {
        $this->orderId = $orderId;
    }
}
