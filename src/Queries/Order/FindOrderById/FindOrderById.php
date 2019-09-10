<?php

namespace App\Queries\Order\FindOrderById;

use App\Infrastructure\Http\AppRequest;

/**
 * @psalm-immutable
 */
final class FindOrderById implements AppRequest
{
    public $orderId;

    public function __construct(string $orderId)
    {
        $this->orderId = $orderId;
    }
}
