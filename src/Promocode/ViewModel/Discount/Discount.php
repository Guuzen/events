<?php
declare(strict_types=1);

namespace App\Promocode\ViewModel\Discount;

use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

/**
 * @DiscriminatorMap(
 *     typeProperty="type",
 *     mapping={
 *         "fixed": "App\Promocode\Model\Discount\FixedDiscount",
 *     }
 * )
 *
 * @psalm-immutable
 */
interface Discount
{

}
