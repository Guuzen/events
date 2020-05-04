<?php

declare(strict_types=1);

namespace App\Product\Model;

use App\Infrastructure\DomainEvent\Event;

/**
 * @psalm-immutable
 */
final class ProductDelivered implements Event
{
    public $productId;

    public $productType;

    public function __construct(ProductId $productId, ProductType $productType)
    {
        $this->productId   = $productId;
        $this->productType = $productType;
    }
}
