<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\EachHeadOfDepartmentInCompanyHasOneUserInfo;

final class User
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var UserInfo
     */
    public $userInfo;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}
