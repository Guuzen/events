<?php

namespace App\Order\Model;

use App\Infrastructure\InlineNormalizer\InlineDenormalizable;
use App\Infrastructure\InlineNormalizer\InlineNormalizable;
use App\Infrastructure\Persistence\DBALTypes\UuidType;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomTypeAnnotation as DBALType;
use App\Infrastructure\Uuid;

/**
 * @DBALType(typeClass=UuidType::class)
 *
 * @InlineNormalizable()
 * @InlineDenormalizable()
 *
 * @template-extends Uuid<OrderId>
 * @psalm-immutable
 */
final class OrderId extends Uuid
{
}
