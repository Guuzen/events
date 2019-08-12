<?php

namespace App\User\Doctrine;

use App\Common\JsonDocumentType;
use App\User\Model\Contacts;

final class UserContactsType extends JsonDocumentType
{
    protected function className(): string
    {
        return Contacts::class;
    }

    public function getName(): string
    {
        return 'app_user_contacts';
    }
}
