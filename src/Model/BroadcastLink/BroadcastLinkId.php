<?php

namespace App\Model\BroadcastLink;

use App\Infrastructure\Persistence\DBALTypes\UuidType;
use App\Infrastructure\Persistence\DBALTypesInitializer\DbalType;
use App\Infrastructure\Uuid;

/**
 * @DbalType(typeClass=UuidType::class)
 *
 * @template-extends Uuid<BroadcastLinkId>
 * @psalm-immutable
 */
final class BroadcastLinkId extends Uuid
{
}
