<?php

namespace App\Promocode\Model\Discount;

use Money\Money;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

/**
 * @DiscriminatorMap(
 *     typeProperty="type",
 *     mapping={
 *         "fixed": "App\Promocode\Model\Discount\FixedDiscount",
 *         "foo": "App\Promocode\Model\NullPromocode"
 *     }
 * )
 *
 * @psalm-immutable
 */
interface Discount
{
    public function apply(Money $price): Money;
}
