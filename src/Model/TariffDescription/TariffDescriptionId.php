<?php

namespace App\Model\TariffDescription;

use App\Infrastructure\InlineNormalizer\Inline;
use App\Infrastructure\Persistence\DBALTypes\UuidType;
use App\Infrastructure\Persistence\DBALTypesInitializer\DbalType;
use App\Infrastructure\Uuid;

/**
 * @Inline
 *
 * @DbalType(typeClass=UuidType::class)
 *
 * @template-extends Uuid<TariffDescriptionId>
 * @psalm-immutable
 */
final class TariffDescriptionId extends Uuid
{
}
