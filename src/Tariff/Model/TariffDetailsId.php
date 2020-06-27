<?php

namespace App\Tariff\Model;

use App\Infrastructure\Uuid;
use App\Infrastructure\Persistence\DoctrineTypesInitializer\InitializerAnnotation as Initializer;

/**
 * @Initializer(doctrineType="App\Infrastructure\Persistence\UuidType::class")
 *
 * @template-extends Uuid<TariffDetailsId>
 * @psalm-immutable
 */
final class TariffDetailsId extends Uuid
{
}
