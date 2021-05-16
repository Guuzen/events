<?php

namespace App\Model\User;

use App\Infrastructure\Persistence\DBALTypes\UuidType;
use App\Infrastructure\Persistence\DBALTypesInitializer\DbalType;
use App\Infrastructure\Uuid;

/**
 * @DbalType(typeClass=UuidType::class)
 *
 * @template-extends Uuid<UserId>
 * @psalm-immutable
 */
final class UserId extends Uuid
{
}
