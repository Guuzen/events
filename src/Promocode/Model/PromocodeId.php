<?php

namespace App\Promocode\Model;

use App\Infrastructure\Uuid;
use App\Infrastructure\Persistence\DoctrineTypesInitializer\InitializerAnnotation as Initializer;

/**
 * @Initializer(doctrineType="App\Infrastructure\Persistence\UuidType::class")
 *
 * @template-extends Uuid<PromocodeId>
 * @psalm-immutable
 */
final class PromocodeId extends Uuid
{
}
