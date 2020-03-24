<?php

declare(strict_types=1);

namespace App\User\Action;

use App\User\Model\Users;

final class UserHandler
{
    private $users;

    public function __construct(Users $users)
    {
        $this->users = $users;
    }

    public function createUser(CreateUser $createUser): void
    {
        $user = $createUser->user();
        $this->users->add($user);
    }
}
