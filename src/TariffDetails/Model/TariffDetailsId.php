<?php

namespace App\TariffDetails\Model;

use App\Infrastructure\Persistence\DBALTypes\UuidType;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomTypeAnnotation as DBALType;
use App\Infrastructure\Uuid;

/**
 * @DBALType(typeClass=UuidType::class)
 *
 * @template-extends Uuid<TariffDetailsId>
 * @psalm-immutable
 */
final class TariffDetailsId extends Uuid
{
}
