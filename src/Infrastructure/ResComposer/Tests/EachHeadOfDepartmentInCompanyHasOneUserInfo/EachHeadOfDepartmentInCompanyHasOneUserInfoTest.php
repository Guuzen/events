<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\EachHeadOfDepartmentInCompanyHasOneUserInfo;

use App\Infrastructure\ResComposer\Link\OneToOne;
use App\Infrastructure\ResComposer\Tests\StubResourceDataLoader;
use App\Infrastructure\ResComposer\Tests\TestCase;

final class EachHeadOfDepartmentInCompanyHasOneUserInfoTest extends TestCase
{
    public function test(): void
    {
        $userId1 = '1';
        $userId2 = '2';
        $user1   = ['id' => $userId1];
        $user2   = ['id' => $userId2];

        $userInfoId1 = 'nonsense';
        $userInfoId2 = 'nonsense';
        $userInfo1   = ['id' => $userInfoId1, 'userId' => $userId1];
        $userInfo2   = ['id' => $userInfoId2, 'userId' => $userId2];

        $company   = [
            'departments' => [
                ['head' => $user1],
                ['head' => $user2],
            ],
        ];
        $companies = [$company];

        $this->composer->addResolver(
            new HeadOfDepartmentHasUser(
                new StubResourceDataLoader([$userInfo1, $userInfo2]),
                new OneToOne('userId'),
                UserInfo::class
            )
        );

        /** @var Company[] $resources */
        $resources = $this->composer->compose($companies, Company::class);

        $headOfDepartment1           = new User($userId1);
        $headOfDepartment1->userInfo = new UserInfo($userInfoId1, $userId1);
        $headOfDepartment2           = new User($userId2);
        $headOfDepartment2->userInfo = new UserInfo($userInfoId2, $userId2);
        self::assertEquals(
            [
                new Company(
                    [
                        new Department($headOfDepartment1),
                        new Department($headOfDepartment2),
                    ]
                ),
            ],
            $resources,
        );
    }
}
