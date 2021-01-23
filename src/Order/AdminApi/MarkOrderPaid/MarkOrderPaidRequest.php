<?php

namespace App\Order\AdminApi\MarkOrderPaid;

use App\Infrastructure\Http\RequestResolver\AppRequest;

/**
 * @psalm-immutable
 */
final class MarkOrderPaidRequest implements AppRequest
{
    public $orderId;

    public $eventId;

    public function __construct(string $orderId, string $eventId)
    {
        $this->orderId = $orderId;
        $this->eventId = $eventId;
    }
}
