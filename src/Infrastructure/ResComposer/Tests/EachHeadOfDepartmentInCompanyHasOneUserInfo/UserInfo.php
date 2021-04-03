<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\EachHeadOfDepartmentInCompanyHasOneUserInfo;

use App\Infrastructure\ResComposer\Resource;

final class UserInfo implements Resource
{
    public $id;
    public $userId;

    public function __construct(string $id, string $userId)
    {
        $this->id     = $id;
        $this->userId = $userId;
    }

    public static function resolvers(): array
    {
        return [];
    }
}
