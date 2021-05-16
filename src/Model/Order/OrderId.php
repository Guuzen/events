<?php

namespace App\Model\Order;

use App\Infrastructure\InlineNormalizer\Inline;
use App\Infrastructure\Persistence\DBALTypes\UuidType;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomTypeAnnotation as DBALType;
use App\Infrastructure\Uuid;

/**
 * @DBALType(typeClass=UuidType::class)
 *
 * @Inline
 *
 * @template-extends Uuid<OrderId>
 * @psalm-immutable
 */
final class OrderId extends Uuid
{
}
