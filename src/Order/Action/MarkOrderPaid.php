<?php

namespace App\Order\Action;

use App\Infrastructure\Http\AppRequest;

final class MarkOrderPaid implements AppRequest
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
