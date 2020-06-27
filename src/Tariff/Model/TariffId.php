<?php

namespace App\Tariff\Model;

use App\Infrastructure\Uuid;
use App\Infrastructure\Persistence\DoctrineTypesInitializer\InitializerAnnotation as Initializer;

/**
 * @Initializer(doctrineType="App\Infrastructure\Persistence\UuidType::class")
 * @template-extends Uuid<TariffId>
 * @psalm-immutable
 */
final class TariffId extends Uuid
{
    // TODO separate classes for every id ? Need ids transitions
}
