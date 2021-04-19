<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests;

use App\Infrastructure\ResComposer\PromiseCollector\CustomCollector;
use App\Infrastructure\ResComposer\Link\OneToOne;
use App\Infrastructure\ResComposer\Promise;
use App\Infrastructure\ResComposer\TypeChecker;

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

        $this->composer->registerLoader(new StubResourceDataLoader([$userInfo1, $userInfo2]));
        $this->composer->registerConfig(
            'company',
            new OneToOne('userId'),
            'userInfo',
            StubResourceDataLoader::class,
            new CustomCollector(
                function (\ArrayObject $company) {
                    $promises = [];
                    TypeChecker::assertThatValueIsIterable($company['departments']);
                    /**
                     * @psalm-suppress MixedArgumentTypeCoercion
                     * @psalm-suppress MixedAssignment
                     */
                    foreach ($company['departments'] as $index => $department) {
                        $promises[] = new Promise(
                        /**
                         * @psalm-suppress MissingClosureReturnType
                         * @psalm-suppress MixedArgumentTypeCoercion
                         */
                            function (\ArrayObject $company) use ($index) {
                                /** @psalm-suppress MixedArrayAccess */
                                return $company['departments'][$index]['head']['id'];
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
            )
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
