<?php

namespace App\Order\Model;

use App\Infrastructure\DomainEvent\Event;

/**
 * @psalm-immutable
 */
final class OrderPaymentByFondyStarted implements Event
{
    public $orderId;

    public function __construct(OrderId $orderId)
    {
        $this->orderId = $orderId;
    }
}
