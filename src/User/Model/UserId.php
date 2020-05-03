<?php

namespace App\User\Model;

use App\Infrastructure\Uuid;

/**
 * @template-extends Uuid<UserId>
 * @psalm-immutable
 */
final class UserId extends Uuid
{
}
