<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\EachUserHasOneUserInfo;

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

    public function promises(): array
    {
        return [];
    }
}
