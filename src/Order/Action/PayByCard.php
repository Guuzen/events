<?php

namespace App\Order\Action;

use App\Infrastructure\Http\AppRequest;

/**
 * @psalm-immutable
 */
final class PayByCard implements AppRequest
{
    public $orderId;

    public function __construct(string $orderId)
    {
        $this->orderId = $orderId;
    }
}
