<?php

declare(strict_types=1);

namespace App\Model\TicketOrder;

use App\Infrastructure\InlineNormalizer\InlineDenormalizable;
use App\Infrastructure\InlineNormalizer\InlineNormalizable;
use App\Infrastructure\Persistence\DBALTypes\UuidType;
use App\Infrastructure\Uuid;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomTypeAnnotation as DBALType;

/**
 * @DBALType(typeClass=UuidType::class)
 *
 * @InlineNormalizable()
 * @InlineDenormalizable()
 *
 * @template-extends Uuid<TicketOrderId>
 * @psalm-immutable
 */
final class TicketOrderId extends Uuid
{
}