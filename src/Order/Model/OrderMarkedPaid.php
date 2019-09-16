<?php

namespace App\Order\Model;

use App\Infrastructure\DomainEvent\Event;
use App\Product\Model\ProductId;

/**
 * @psalm-immutable
 */
final class OrderMarkedPaid implements Event
{
    public $productId;

    public function __construct(ProductId $productId)
    {
        $this->productId = $productId;
    }
}
