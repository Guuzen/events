<?php

namespace App\Model\Order;

use App\Infrastructure\InlineNormalizer\Inline;
use App\Infrastructure\Persistence\DBALTypes\UuidType;
use App\Infrastructure\Persistence\DBALTypesInitializer\DbalType;
use App\Infrastructure\Uuid;

/**
 * @DbalType(typeClass=UuidType::class)
 *
 * @Inline
 *
 * @template-extends Uuid<OrderId>
 * @psalm-immutable
 */
final class OrderId extends Uuid
{
}
