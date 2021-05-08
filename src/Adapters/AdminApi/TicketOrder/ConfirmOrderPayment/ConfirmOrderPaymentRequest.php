<?php

namespace App\Adapters\AdminApi\TicketOrder\ConfirmOrderPayment;

use App\Infrastructure\Http\RequestResolver\AppRequest;

/**
 * @psalm-immutable
 */
final class ConfirmOrderPaymentRequest implements AppRequest
{
    public $orderId;

    public $eventId;

    public function __construct(string $orderId, string $eventId)
    {
        $this->orderId = $orderId;
        $this->eventId = $eventId;
    }
}
