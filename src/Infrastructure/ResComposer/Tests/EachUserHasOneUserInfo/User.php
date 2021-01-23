<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\EachUserHasOneUserInfo;

use App\Infrastructure\ResComposer\Promise;
use App\Infrastructure\ResComposer\Resource;
use App\Infrastructure\ResComposer\Tests\TestPromiseGroupResolver;

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

    public function promises(): array
    {
        return [Promise::withProperties('id', 'userInfo', $this, TestPromiseGroupResolver::class)];
    }
}
