<?php

namespace App\Product\Doctrine;

use App\Common\JsonDocumentType;
use App\Product\Model\ProductType;

final class ProductTypeType extends JsonDocumentType
{
    protected function className(): string
    {
        return ProductType::class;
    }

    public function getName(): string
    {
        return 'app_product_type';
    }
}
