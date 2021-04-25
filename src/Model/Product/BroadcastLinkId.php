<?php

namespace App\Model\Product;

use App\Infrastructure\Persistence\DBALTypes\UuidType;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomTypeAnnotation as DBALType;
use App\Infrastructure\Uuid;

/**
 * @DBALType(typeClass=UuidType::class)
 *
 * @template-extends Uuid<BroadcastLinkId>
 * @psalm-immutable
 */
final class BroadcastLinkId extends Uuid
{
}