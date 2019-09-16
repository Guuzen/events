<?php

namespace App\Product\Doctrine;

use App\Common\JsonDocumentType;
use App\Product\Model\ProductType;

final class ProductTypeType extends JsonDocumentType
{
    public function getName(): string
    {
        return 'app_product_type';
    }

    protected function className(): string
    {
        return ProductType::class;
    }
}
