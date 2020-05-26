<?php

namespace App\Product\Action\CreateTicket;

use App\Event\Model\EventId;
use App\Infrastructure\Http\RequestResolver\AppRequest;
use App\Order\Model\OrderId;

/**
 * @psalm-immutable
 */
final class CreateTicket implements AppRequest
{
    public $eventId;

    public $orderId;

    public $asOf;

    public function __construct(EventId $eventId, OrderId $orderId, \DateTimeImmutable $asOf)
    {
        $this->eventId = $eventId;
        $this->orderId = $orderId;
        $this->asOf    = $asOf;
    }
}
