<?php

namespace App\Model\User;

use App\Infrastructure\Persistence\DBALTypes\JsonDocumentType;
use App\Infrastructure\Persistence\DBALTypesInitializer\DbalType;

/**
 * @DbalType(typeClass=JsonDocumentType::class)
 */
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
