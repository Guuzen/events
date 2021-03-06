<?php

declare(strict_types=1);

namespace App\Promocode\Model;

use App\Event\Model\EventId;
use App\Infrastructure\DomainEvent\Event;
use App\Order\Model\OrderId;
use App\Promocode\Model\Discount\Discount;

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
