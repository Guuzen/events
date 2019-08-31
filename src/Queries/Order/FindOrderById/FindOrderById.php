<?php

namespace App\Queries\Order\FindOrderById;

use App\Infrastructure\Http\AppRequest;

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
