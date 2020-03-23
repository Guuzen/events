<?php

declare(strict_types=1);

namespace App\User\Action;

use App\User\Model\User;
use App\User\Model\UserId;
use App\User\Model\Users;

final class UserHandler
{
    private $users;

    public function __construct(Users $users)
    {
        $this->users = $users;
    }

    public function createUser(CreateUser $createUser): UserId
    {
        $userId = UserId::new();
        $user   = new User(
            $userId,
            $createUser->fullName,
            $createUser->contacts
        );

        $this->users->add($user);

        return $userId;
    }
}
