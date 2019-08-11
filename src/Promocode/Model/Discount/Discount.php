<?php

namespace App\Promocode\Model\Discount;

use Money\Money;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

/**
 * @DiscriminatorMap(
 *     typeProperty="type",
 *     mapping={
 *         "regular": "App\Promocode\Model\RegularPromocode",
 *         "null": "App\Promocode\Model\NullPromocode"
 *     }
 * )
 */
interface Discount
{
    public function apply(Money $price): Money;
}
