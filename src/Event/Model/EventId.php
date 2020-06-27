<?php

namespace App\Event\Model;

use App\Infrastructure\Persistence\DBALTypes\UuidType;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomTypeAnnotation as DBALType;
use App\Infrastructure\Uuid;

/**
 * @DBALType(typeClass=UuidType::class)
 *
 * @template-extends Uuid<EventId>
 * @psalm-immutable
 */
final class EventId extends Uuid
{
}
