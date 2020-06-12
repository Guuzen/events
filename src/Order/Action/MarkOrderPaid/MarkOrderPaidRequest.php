<?php

namespace App\Order\Action\MarkOrderPaid;

use App\Event\Model\EventId;
use App\Infrastructure\Http\RequestResolver\AppRequest;
use App\Order\Model\OrderId;

/**
 * @psalm-immutable
 */
final class MarkOrderPaidRequest implements AppRequest
{
    private $orderId;

    private $eventId;

    public function __construct(string $orderId, string $eventId)
    {
        $this->orderId = $orderId;
        $this->eventId = $eventId;
    }

    public function createMarkOrderPaid(): MarkOrderPaid
    {
        return new MarkOrderPaid(new OrderId($this->orderId), new EventId($this->eventId));
    }
}
