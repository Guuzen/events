<?php

declare(strict_types=1);

namespace App\Order\Action\MarkOrderPaid;

use App\Event\Model\EventId;
use App\Order\Model\OrderId;

/**
 * @psalm-immutable
 */
final class MarkOrderPaid
{
    public $orderId;

    public $eventId;

    public function __construct(OrderId $orderId, EventId $eventId)
    {
        $this->orderId = $orderId;
        $this->eventId = $eventId;
    }
}
