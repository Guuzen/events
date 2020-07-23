<?php

namespace App\TariffDescription;

use App\Infrastructure\InlineNormalizer\InlineDenormalizable;
use App\Infrastructure\Persistence\DBALTypes\UuidType;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomTypeAnnotation as DBALType;
use App\Infrastructure\Uuid;

/**
 * @InlineDenormalizable()
 *
 * @DBALType(typeClass=UuidType::class)
 *
 * @template-extends Uuid<TariffDescriptionId>
 * @psalm-immutable
 */
final class TariffDescriptionId extends Uuid
{
}
