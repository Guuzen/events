<?php

namespace App\User\Model;

use App\Infrastructure\Persistence\DBALTypes\UuidType;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomTypeAnnotation as DoctrineType;
use App\Infrastructure\Uuid;

/**
 * @DoctrineType(typeClass=UuidType::class)
 *
 * @template-extends Uuid<UserId>
 * @psalm-immutable
 */
final class UserId extends Uuid
{
}
