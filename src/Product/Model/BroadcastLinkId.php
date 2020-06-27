<?php

namespace App\Product\Model;

use App\Infrastructure\Persistence\DBALTypes\UuidType;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomTypeAnnotation as DoctrineType;
use App\Infrastructure\Uuid;

/**
 * @DoctrineType(typeClass=UuidType::class)
 *
 * @template-extends Uuid<BroadcastLinkId>
 * @psalm-immutable
 */
final class BroadcastLinkId extends Uuid
{
}
