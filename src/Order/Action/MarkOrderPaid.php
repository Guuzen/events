<?php

namespace App\Order\Action;

use App\Infrastructure\Http\RequestResolver\AppRequest;

/**
 * @psalm-immutable
 */
final class MarkOrderPaid implements AppRequest
{
    public $orderId;

    public $eventId;

    public function __construct(string $orderId, string $eventId)
    {
        $this->orderId = $orderId;
        $this->eventId = $eventId;
    }
}
