<?php

namespace App\Order\Model;

use App\Infrastructure\Persistence\DBALTypes\UuidType;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomTypeAnnotation as DoctrineType;
use App\Infrastructure\Uuid;

/**
 * @DoctrineType(typeClass=UuidType::class)
 *
 * @template-extends Uuid<OrderId>
 * @psalm-immutable
 */
final class OrderId extends Uuid
{
}
