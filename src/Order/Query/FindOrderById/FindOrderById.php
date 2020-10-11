<?php

declare(strict_types=1);

namespace App\Order\Query\FindOrderById;

use App\Order\Model\OrderId;

/**
 * @psalm-immutable
 */
final class FindOrderById
{
    public $orderId;

    public function __construct(OrderId $orderId)
    {
        $this->orderId = $orderId;
    }
}
