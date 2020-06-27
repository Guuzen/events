<?php

namespace App\Product\Model;

use App\Infrastructure\Uuid;
use App\Infrastructure\Persistence\DoctrineTypesInitializer\InitializerAnnotation as Initializer;

/**
 * @Initializer(doctrineType="App\Infrastructure\Persistence\UuidType::class")
 *
 * @template-extends Uuid<TicketId>
 * @psalm-immutable
 */
final class TicketId extends Uuid
{
}
