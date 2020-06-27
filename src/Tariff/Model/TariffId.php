<?php

namespace App\Tariff\Model;

use App\Infrastructure\Persistence\DBALTypes\UuidType;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomTypeAnnotation as DoctrineType;
use App\Infrastructure\Uuid;

/**
 * @DoctrineType(typeClass=UuidType::class)
 *
 * @template-extends Uuid<TariffId>
 * @psalm-immutable
 */
final class TariffId extends Uuid
{
    // TODO separate classes for every id ? Need ids transitions
}
