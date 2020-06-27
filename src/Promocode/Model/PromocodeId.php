<?php

namespace App\Promocode\Model;

use App\Infrastructure\Persistence\DBALTypes\UuidType;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomTypeAnnotation as DBALType;
use App\Infrastructure\Uuid;

/**
 * @DBALType(typeClass=UuidType::class)
 *
 * @template-extends Uuid<PromocodeId>
 * @psalm-immutable
 */
final class PromocodeId extends Uuid
{
}
