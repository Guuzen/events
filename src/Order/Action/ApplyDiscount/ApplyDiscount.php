<?php

declare(strict_types=1);

namespace App\Order\Action\ApplyDiscount;

use App\Event\Model\EventId;
use App\Order\Model\OrderId;
use App\Promocode\Model\Discount\Discount;

/**
 * @psalm-immutable
 */
final class ApplyDiscount
{
    public $eventId;

    public $orderId;

    public $discount;

    public function __construct(EventId $eventId, OrderId $orderId, Discount $discount)
    {
        $this->eventId  = $eventId;
        $this->orderId  = $orderId;
        $this->discount = $discount;
    }
}
