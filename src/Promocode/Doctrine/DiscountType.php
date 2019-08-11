<?php

namespace App\Promocode\Doctrine;

use App\Common\JsonDocumentTypeNew;
use App\Promocode\Model\Discount\Discount;

final class DiscountType extends JsonDocumentTypeNew
{
    protected function className(): string
    {
        return Discount::class;
    }

    public function getName()
    {
        return 'app_promocode_discount';
    }
}
