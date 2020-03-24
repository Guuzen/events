<?php

declare(strict_types=1);

namespace App\User\Action;

use App\User\Model\Contacts;
use App\User\Model\FullName;
use App\User\Model\User;
use App\User\Model\UserId;

final class CreateUser
{
    private $userId;

    private $firstName;

    private $lastName;

    private $email;

    private $phone;

    public function __construct(string $userId, string $firstName, string $lastName, string $email, string $phone)
    {
        $this->userId    = $userId;
        $this->firstName = $firstName;
        $this->lastName  = $lastName;
        $this->email     = $email;
        $this->phone     = $phone;
    }

    public function user(): User
    {
        return new User(
            new UserId($this->userId),
            new FullName($this->firstName, $this->lastName),
            new Contacts($this->email, $this->phone)
        );
    }
}
