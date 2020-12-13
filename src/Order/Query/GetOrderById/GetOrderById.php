<?php

declare(strict_types=1);

namespace App\Order\Query\GetOrderById;

use App\Order\Model\OrderId;

/**
 * @psalm-immutable
 */
final class GetOrderById
{
    public $orderId;

    public function __construct(OrderId $orderId)
    {
        $this->orderId = $orderId;
    }
}
