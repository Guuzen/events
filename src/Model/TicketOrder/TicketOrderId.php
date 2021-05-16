<?php

declare(strict_types=1);

namespace App\Model\TicketOrder;

use App\Infrastructure\InlineNormalizer\Inline;
use App\Infrastructure\Persistence\DBALTypes\UuidType;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomTypeAnnotation as DBALType;
use App\Infrastructure\Uuid;

/**
 * @DBALType(typeClass=UuidType::class)
 *
 * @Inline
 *
 * @template-extends Uuid<TicketOrderId>
 * @psalm-immutable
 */
final class TicketOrderId extends Uuid
{
}