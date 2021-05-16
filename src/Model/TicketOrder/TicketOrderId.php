<?php

declare(strict_types=1);

namespace App\Model\TicketOrder;

use App\Infrastructure\InlineNormalizer\Inline;
use App\Infrastructure\Persistence\DBALTypes\UuidType;
use App\Infrastructure\Persistence\DBALTypesInitializer\DbalType;
use App\Infrastructure\Uuid;

/**
 * @DbalType(typeClass=UuidType::class)
 *
 * @Inline
 *
 * @template-extends Uuid<TicketOrderId>
 * @psalm-immutable
 */
final class TicketOrderId extends Uuid
{
}