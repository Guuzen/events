<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests;

use App\Infrastructure\ResComposer\Config\MainResource;
use App\Infrastructure\ResComposer\Config\RelatedResource;
use App\Infrastructure\ResComposer\Link\OneToOne;
use App\Infrastructure\ResComposer\Promise;
use App\Infrastructure\ResComposer\PromiseCollector\CustomCollector;

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

        $this->composer->registerRelation(
            new MainResource(
                'company',
                new CustomCollector(
                    function (\ArrayObject $company) {
                        $promises = [];
                        /** @var array<int, array{head: array{id: string}}> $departments */
                        $departments = $company['departments'];
                        foreach ($departments as $index => $department) {
                            $promises[] = new Promise(
                                function (\ArrayObject $company) use ($department): string {
                                    return $department['head']['id'];
                                },
                                function (\ArrayObject $company, \ArrayObject $userInfo) use ($index) {
                                    /**
                                     * @psalm-suppress MixedAssignment
                                     * @psalm-suppress MixedArrayAssignment
                                     */
                                    $company['departments'][$index]['head']['userInfo'] = $userInfo;
                                },
                                $company,
                            );
                        }

                        return $promises;
                    }
                ),
            ),
            new OneToOne(),
            new RelatedResource(
                'userInfo',
                'userId',
                new StubResourceDataLoader([$userInfo1, $userInfo2])
            ),
        );

        $resources = $this->composer->compose($companies, 'company');

        self::assertEquals(
            [
                [
                    'departments' => [
                        [
                            'head' => [
                                'id'       => $userId1,
                                'userInfo' => $userInfo1,
                            ],
                        ],
                        [
                            'head' => [
                                'id'       => $userId2,
                                'userInfo' => $userInfo2,
                            ],
                        ],
                    ],
                ]
            ],
            $resources,
        );
    }
}
