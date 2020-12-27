<?php

declare(strict_types=1);

namespace App\Infrastructure\ArrayComposer\Tests\Integrated;

use App\Infrastructure\ArrayComposer\Path;
use App\Infrastructure\ArrayComposer\ResourceProviders;
use App\Infrastructure\ArrayComposer\Schema;
use PHPUnit\Framework\TestCase;

final class EachUserHasOneUserInfoTest extends TestCase
{
    public function test(): void
    {
        $userId1   = '1';
        $userId2   = '2';
        $user1     = ['id' => $userId1];
        $user2     = ['id' => $userId2];
        $users     = [
            $user1,
            $user2,
        ];
        $userInfo1 = ['id' => 'nonsense', 'userId' => $userId1];
        $userInfo2 = ['id' => 'nonsense', 'userId' => $userId2];

        $schema = new Schema();
        $schema->oneToOne(
            'userProvider',
            new Path(['id']),
            'userInfoProvider',
            new Path(['userId']),
            'userInfo'
        );
        $providers = new ResourceProviders(
            [
                'userInfoProvider' => new StubResourceProvider([$userInfo1, $userInfo2]),
            ]
        );

        $result = $schema->collect($users, 'userProvider', $providers);

        self::assertEquals(
            [
                ['id' => $userId1, 'userInfo' => $userInfo1],
                ['id' => $userId2, 'userInfo' => $userInfo2],
            ],
            $result
        );
    }
}
