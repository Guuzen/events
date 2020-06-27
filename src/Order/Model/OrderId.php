<?php

namespace App\Order\Model;

use App\Infrastructure\Uuid;
use App\Infrastructure\Persistence\DoctrineTypesInitializer\InitializerAnnotation as Initializer;

/**
 * @Initializer(doctrineType="App\Infrastructure\Persistence\UuidType::class")
 *
 * @template-extends Uuid<OrderId>
 * @psalm-immutable
 */
final class OrderId extends Uuid
{
}
