<?php

namespace App\User\Doctrine;

use App\Infrastructure\Persistence\UuidType;
use App\User\Model\UserId;

/**
 * @template-extends UuidType<UserId>
 */
final class UserIdType extends UuidType
{
    public function getName(): string
    {
        return 'app_user_id';
    }

    protected function className(): string
    {
        return UserId::class;
    }
}
