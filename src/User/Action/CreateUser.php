<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Order\Model\OrderId;
use App\User\Model\Contacts;
use App\User\Model\FullName;
use App\User\Model\User;
use App\User\Model\UserId;

final class CreateUser
{
    private $userId;

    private $orderId;

    private $firstName;

    private $lastName;

    private $email;

    private $phone;

    public function __construct(string $userId, OrderId $orderId, string $firstName, string $lastName, string $email, string $phone)
    {
        $this->userId    = $userId;
        $this->orderId   = $orderId;
        $this->firstName = $firstName;
        $this->lastName  = $lastName;
        $this->email     = $email;
        $this->phone     = $phone;
    }

    public function user(): User
    {
        return new User(
            new UserId($this->userId),
            $this->orderId,
            new FullName($this->firstName, $this->lastName),
            new Contacts($this->email, $this->phone)
        );
    }
}
