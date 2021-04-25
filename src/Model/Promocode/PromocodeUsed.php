<?php

declare(strict_types=1);

namespace App\Model\Promocode;

use App\Model\Event\EventId;
use App\Infrastructure\DomainEvent\Event;
use App\Model\Order\OrderId;
use App\Model\Promocode\Discount\Discount;

/**
 * @psalm-immutable
 */
final class PromocodeUsed implements Event
{
    public $eventId;

    public $promocodeId;

    public $orderId;

    public $discount;

    public function __construct(EventId $eventId, PromocodeId $promocodeId, OrderId $orderId, Discount $discount)
    {
        $this->eventId     = $eventId;
        $this->promocodeId = $promocodeId;
        $this->orderId     = $orderId;
        $this->discount    = $discount;
    }
}
