<?php

namespace App\Model\TariffDescription;

use App\Infrastructure\InlineNormalizer\Inline;
use App\Infrastructure\Persistence\DBALTypes\UuidType;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomTypeAnnotation as DBALType;
use App\Infrastructure\Uuid;

/**
 * @Inline
 *
 * @DBALType(typeClass=UuidType::class)
 *
 * @template-extends Uuid<TariffDescriptionId>
 * @psalm-immutable
 */
final class TariffDescriptionId extends Uuid
{
}
