<?php

namespace App\Model\TariffDescription;

use App\Infrastructure\InlineNormalizer\InlineDenormalizable;
use App\Infrastructure\InlineNormalizer\InlineNormalizable;
use App\Infrastructure\Persistence\DBALTypes\UuidType;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomTypeAnnotation as DBALType;
use App\Infrastructure\Uuid;

/**
 * @InlineNormalizable()
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
