<?php

namespace App\Model\Promocode;

use App\Infrastructure\InlineNormalizer\Inline;
use App\Infrastructure\Persistence\DBALTypes\UuidType;
use App\Infrastructure\Persistence\DBALTypesInitializer\DbalType;
use App\Infrastructure\Uuid;

/**
 * @DbalType(typeClass=UuidType::class)
 *
 * @Inline
 *
 * @template-extends Uuid<PromocodeId>
 * @psalm-immutable
 */
final class PromocodeId extends Uuid
{
}
