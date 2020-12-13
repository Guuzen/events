<?php

declare(strict_types=1);

namespace Tests\Infrastructure\JoinResponse\Integrated;

use App\Infrastructure\ArrayComposer\Path\Path;
use App\Infrastructure\ArrayComposer\ResourceProviders;
use App\Infrastructure\ArrayComposer\Schema;
use PHPUnit\Framework\TestCase;

final class EachNestedUserHasOneUserInfoTest extends TestCase
{
    public function test(): void
    {
        $userId1   = '1';
        $userId2   = '2';
        $user1     = ['id' => $userId1];
        $user2     = ['id' => $userId2];
        $userInfo1 = ['id' => 'nonsense', 'userId' => $userId1];
        $userInfo2 = ['id' => 'nonsense', 'userId' => $userId2];
        $root      = [
            'foo' => [
                ['user' => $user1],
                ['user' => $user2],
            ],
        ];

        $userInfoProviderId = 'userInfoProvider';

        $schema = new Schema('userProvider');

        $schema->oneToOne(
            new Schema($userInfoProviderId),
            new Path(['foo', '[]', 'user', 'id']),
            new Path(['userId']),
            'userInfo'
        );
        $providers = new ResourceProviders(
            [
                $userInfoProviderId => new StubResourceProvider([$userInfo1, $userInfo2]),
            ]
        );

        $result = $schema->collect([$root], $providers);

        self::assertEquals(
            [
                [
                    'foo' => [
                        ['user' => ['id' => $userId1, 'userInfo' => $userInfo1]],
                        ['user' => ['id' => $userId2, 'userInfo' => $userInfo2]],
                    ],
                ],
            ],
            $result
        );
    }
}
