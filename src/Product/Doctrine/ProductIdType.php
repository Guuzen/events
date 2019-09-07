<?php

namespace App\Product\Doctrine;

use App\Infrastructure\Persistence\UuidType;
use App\Product\Model\ProductId;

/**
 * @template-extends UuidType<ProductId>
 */
final class ProductIdType extends UuidType
{
    public function getName(): string
    {
        return 'app_product_id';
    }

    protected function className(): string
    {
        return ProductId::class;
    }
}
