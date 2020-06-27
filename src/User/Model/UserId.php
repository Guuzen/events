<?php

namespace App\User\Model;

use App\Infrastructure\Persistence\DBALTypes\UuidType;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomTypeAnnotation as DBALType;
use App\Infrastructure\Uuid;

/**
 * @DBALType(typeClass=UuidType::class)
 *
 * @template-extends Uuid<UserId>
 * @psalm-immutable
 */
final class UserId extends Uuid
{
}
