<?php
declare(strict_types=1);

namespace App\User\Doctrine;

use App\Common\JsonDocumentType as JsonDocumentTypeAlias;
use App\User\Model\Contacts;

final class UserContactsType extends JsonDocumentTypeAlias
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
