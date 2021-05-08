<?php

namespace App\Adapters\FondyApi\ConfirmOrderPayment;

use App\Infrastructure\Http\RequestResolver\AppRequest;

/**
 * @psalm-immutable
 */
final class ConfirmOrderPaymentRequest implements AppRequest
{
    public $orderId;

    public function __construct(string $orderId)
    {
        $this->orderId = $orderId;
    }
}
