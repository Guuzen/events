<?php

namespace App\Order\Fondy\MarkOrderPaidByFondy;

use App\Infrastructure\Http\RequestResolver\AppRequest;

/**
 * @psalm-immutable
 */
final class MarkOrderPaidRequest implements AppRequest
{
    public $orderId;

    public function __construct(string $orderId)
    {
        $this->orderId = $orderId;
    }
}
