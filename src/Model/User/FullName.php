<?php

namespace App\Model\User;

use App\Infrastructure\Persistence\DBALTypes\JsonDocumentType;
use App\Infrastructure\Persistence\DBALTypesInitializer\DbalType;

/**
 * @DbalType(typeClass=JsonDocumentType::class)
 */
final class FullName
{
    private $firstName;

    private $lastName;

    public function __construct(string $firstName, string $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName  = $lastName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }
}
