<?php

namespace App\Tariff\Model;

use App\Infrastructure\Persistence\DBALTypes\UuidType;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomTypeAnnotation as DoctrineType;
use App\Infrastructure\Uuid;

/**
 * @DoctrineType(typeClass=UuidType::class)
 *
 * @template-extends Uuid<TariffDetailsId>
 * @psalm-immutable
 */
final class TariffDetailsId extends Uuid
{
}
