<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\EachUserHasOneUserInfo;

use App\Infrastructure\ResComposer\Resource;

final class User implements Resource
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

    public static function resolvers(): array
    {
        return [UserHasUserInfo::class];
    }
}
