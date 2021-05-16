<?php

namespace App\Model\Ticket;

use App\Infrastructure\Persistence\DBALTypes\UuidType;
use App\Infrastructure\Persistence\DBALTypesInitializer\DbalType;
use App\Infrastructure\Uuid;

/**
 * @DbalType(typeClass=UuidType::class)
 *
 * @template-extends Uuid<TicketId>
 * @psalm-immutable
 */
final class TicketId extends Uuid
{
}
