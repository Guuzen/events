<?php

namespace App\Event\Model;

use App\Infrastructure\Uuid;
use App\Infrastructure\Persistence\DoctrineTypesInitializer\InitializerAnnotation as Initializer;

/**
 * @Initializer(doctrineType="App\Infrastructure\Persistence\UuidType::class")
 *
 * @template-extends Uuid<EventId>
 * @psalm-immutable
 */
final class EventId extends Uuid
{
}
