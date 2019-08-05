<?php

namespace App\User\Model;

final class Contacts
{
    private $email;

    private $phone;

    public function __construct(string $email, string $phone)
    {
        $this->email = $email;
        $this->phone = $phone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }
}
