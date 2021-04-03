<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\EachUserHasOneUserInfo;

use App\Infrastructure\ResComposer\Link\OneToOne;
use App\Infrastructure\ResComposer\Tests\StubResourceDataLoader;
use App\Infrastructure\ResComposer\Tests\TestCase;

final class EachUserHasOneUserInfoTest extends TestCase
{
    public function test(): void
    {
        $userId1     = '1';
        $userId2     = '2';
        $user1       = ['id' => $userId1];
        $user2       = ['id' => $userId2];
        $users       = [
            $user1,
            $user2,
        ];
        $userInfoId1 = 'nonsense';
        $userInfoId2 = 'nonsense';
        $userInfo1   = ['id' => $userInfoId1, 'userId' => $userId1];
        $userInfo2   = ['id' => $userInfoId2, 'userId' => $userId2];

        $this->composer->addResolver(
            new UserHasUserInfo(
                new StubResourceDataLoader([$userInfo1, $userInfo2]),
                new OneToOne('userId'),
                UserInfo::class
            )
        );

        /** @var User[] $resources */
        $resources = $this->composer->compose($users, User::class);

        $user1           = new User($userId1);
        $user1->userInfo = new UserInfo($userInfoId1, $userId1);
        $user2           = new User($userId2);
        $user2->userInfo = new UserInfo($userInfoId2, $userId2);
        self::assertEquals(
            [
                $user1,
                $user2,
            ],
            $resources,
        );
    }
}
