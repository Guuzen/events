<?php

namespace App\Promocode\Model\Discount;

use App\Infrastructure\Persistence\DBALTypes\JsonDocumentType;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomTypeAnnotation as DBALType;
use Money\Money;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

/**
 * @DBALType(typeClass=JsonDocumentType::class)
 *
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
