<?php

declare(strict_types=1);

namespace App\User\Action;

use App\User\Model\Contacts;
use App\User\Model\FullName;

final class CreateUser
{
    public $fullName;

    public $contacts;

    public function __construct(FullName $fullName, Contacts $contacts)
    {
        $this->fullName = $fullName;
        $this->contacts = $contacts;
    }
}
