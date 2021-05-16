<?php

namespace App\Model\Tariff;

use App\Infrastructure\InlineNormalizer\Inline;
use App\Infrastructure\Persistence\DBALTypes\UuidType;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomTypeAnnotation as DBALType;
use App\Infrastructure\Uuid;

/**
 * @DBALType(typeClass=UuidType::class)
 *
 * @Inline
 *
 * @template-extends Uuid<TariffId>
 * @psalm-immutable
 */
final class TariffId extends Uuid
{
    // TODO separate classes for every id ? Need ids transitions
}
