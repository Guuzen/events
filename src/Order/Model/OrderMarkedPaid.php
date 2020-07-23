<?php

namespace App\Order\Model;

use App\Event\Model\EventId;
use App\Infrastructure\DomainEvent\Event;
use App\Tariff\Model\ProductType;

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
