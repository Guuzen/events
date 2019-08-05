<?php

namespace App\Promocode\Doctrine;

use App\Common\JsonDocumentType;
use App\Promocode\Model\Discount\Discount;

final class PromocodeDiscountType extends JsonDocumentType
{
    public const TYPE = 'app_promocode_discount';

    protected function className(): string
    {
        return Discount::class;
    }

    public function getName(): string
    {
        return self::TYPE;
    }
}
