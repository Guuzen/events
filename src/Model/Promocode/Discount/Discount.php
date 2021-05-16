<?php

namespace App\Model\Promocode\Discount;

use App\Infrastructure\Persistence\DBALTypes\JsonDocumentType;
use App\Infrastructure\Persistence\DBALTypesInitializer\DbalType;
use Money\Money;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

/**
 * @DbalType(typeClass=JsonDocumentType::class)
 *
 * @DiscriminatorMap(
 *     typeProperty="type",
 *     mapping={
 *         "fixed": FixedDiscount::class,
 *         "foo": "App\Promocode\Model\NullPromocode"
 *     }
 * )
 *
 * @psalm-immutable
 */
interface Discount
{
    public function applyTo(Money $price): Money;
}
