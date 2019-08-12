<?php

namespace App\Promocode\Doctrine;

use App\Common\JsonDocumentType;
use App\Promocode\Model\Discount\Discount;

final class PromocodeDiscountType extends JsonDocumentType
{
    protected function className(): string
    {
        return Discount::class;
    }

    public function getName(): string
    {
        return 'app_promocode_discount';
    }
}
