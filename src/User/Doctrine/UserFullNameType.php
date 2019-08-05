<?php

namespace App\User\Doctrine;

use App\Common\JsonDocumentType;
use App\User\Model\FullName;

final class UserFullNameType extends JsonDocumentType
{
    protected function className(): string
    {
        return FullName::class;
    }

    public function getName(): string
    {
        return 'app_user_fullname_type';
    }
}
