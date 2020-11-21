<?php

declare(strict_types=1);

namespace Tests\Infrastructure\JoinResponse\Integrated;

use App\Infrastructure\ResponseComposer\ResourceProviders;
use App\Infrastructure\ResponseComposer\Schema;
use App\Infrastructure\ResponseComposer\SchemaProvider;
use PHPUnit\Framework\TestCase;

final class EachUserHasOneUserInfoTest extends TestCase
{
    public function test(): void
    {
        $userKey1  = '1';
        $userKey2  = '2';
        $user1     = ['id' => $userKey1];
        $user2     = ['id' => $userKey2];
        $users     = [
            $user1,
            $user2,
        ];
        $userInfo1 = ['id' => 'nonsense', 'userId' => $userKey1];
        $userInfo2 = ['id' => 'nonsense', 'userId' => $userKey2];

        $schema    = User::schema();
        $providers = new ResourceProviders(
            [
                UserInfo::class => new StubResourceProvider([$userInfo1, $userInfo2]),
            ]
        );

        $groupBuilder = $schema->collect($users, $providers);
        $result       = $groupBuilder->build();

        self::assertEquals(
            [
                new User($user1, new UserInfo($userInfo1)),
                new User($user2, new UserInfo($userInfo2)),
            ],
            $result
        );
    }
}

final class User implements SchemaProvider
{
    private array $user;
    private UserInfo $userInfo;

    public function __construct(array $user, UserInfo $userInfo)
    {
        $this->user     = $user;
        $this->userInfo = $userInfo;
    }

    public static function schema(): Schema
    {
        $schema = new Schema(self::class);
        $schema->oneToOne(
            UserInfo::schema(),
            fn(array $user) => (string)$user['id'],
            fn(array $userInfo) => (string)$userInfo['userId'],
        );

        return $schema;
    }
}

final class UserInfo implements SchemaProvider
{
    private array $userInfo;

    public function __construct(array $userInfo)
    {
        $this->userInfo = $userInfo;
    }

    public static function schema(): Schema
    {
        return new Schema(self::class);
    }
}
