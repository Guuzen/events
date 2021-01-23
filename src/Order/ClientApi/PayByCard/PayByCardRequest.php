<?php

namespace App\Order\ClientApi\PayByCard;

use App\Infrastructure\Http\RequestResolver\AppRequest;

/**
 * @psalm-immutable
 */
final class PayByCardRequest implements AppRequest
{
    public $orderId;

    public function __construct(string $orderId)
    {
        $this->orderId = $orderId;
    }
}
