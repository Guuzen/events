<?php

namespace App\Model\Order;

use App\Model\Event\EventId;
use App\Infrastructure\DomainEvent\Event;
use App\Model\Tariff\ProductType;

/**
 * @psalm-immutable
 */
final class OrderMarkedPaid implements Event
{
    public $eventId;

    public $productType;

    public $orderId;

    public function __construct(EventId $eventId, ProductType $productType, OrderId $orderId)
    {
        $this->eventId     = $eventId;
        $this->productType = $productType;
        $this->orderId     = $orderId;
    }
}
