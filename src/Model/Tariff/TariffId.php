<?php

namespace App\Model\Tariff;

use App\Infrastructure\InlineNormalizer\InlineDenormalizable;
use App\Infrastructure\InlineNormalizer\InlineNormalizable;
use App\Infrastructure\Persistence\DBALTypes\UuidType;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomTypeAnnotation as DBALType;
use App\Infrastructure\Uuid;

/**
 * @DBALType(typeClass=UuidType::class)
 *
 * @InlineNormalizable()
 * @InlineDenormalizable()
 *
 * @template-extends Uuid<TariffId>
 * @psalm-immutable
 */
final class TariffId extends Uuid
{
    // TODO separate classes for every id ? Need ids transitions
}
