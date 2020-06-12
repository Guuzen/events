<?php

declare(strict_types=1);

namespace App\Order\Action\PayByCard;

use App\Event\Model\EventId;
use App\Order\Model\OrderId;

/**
 * @psalm-immutable
 */
final class PayByCard
{
    public $orderId;

    public $eventId;

    public function __construct(OrderId $orderId, EventId $eventId)
    {
        $this->orderId = $orderId;
        $this->eventId = $eventId;
    }
}
