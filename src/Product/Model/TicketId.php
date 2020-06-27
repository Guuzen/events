<?php

namespace App\Product\Model;

use App\Infrastructure\Persistence\DBALTypes\UuidType;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomTypeAnnotation as DBALType;
use App\Infrastructure\Uuid;

/**
 * @DBALType(typeClass=UuidType::class)
 *
 * @template-extends Uuid<TicketId>
 * @psalm-immutable
 */
final class TicketId extends Uuid
{
}
