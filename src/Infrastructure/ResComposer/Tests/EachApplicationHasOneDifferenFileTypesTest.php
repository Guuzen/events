<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests;

use App\Infrastructure\ResComposer\Link\OneToOne;
use App\Infrastructure\ResComposer\PromiseCollector\MultipleSimpleCollector;

final class EachApplicationHasOneDifferenFileTypesTest extends TestCase
{
    public function test(): void
    {
        $fileAId = '1';
        $fileBId = '2';
        $fileA   = [
            'id' => $fileAId,
        ];
        $fileB   = [
            'id' => $fileBId,
        ];

        $applicationId = 'nonsense';
        $application   = [
            'id'    => $applicationId,
            'fileA' => $fileAId,
            'fileB' => $fileBId,
        ];
        $applications  = [$application];

        $this->composer->registerConfig(
            'application',
            new OneToOne('id'),
            'file',
            new StubResourceDataLoader([$fileA, $fileB]),
            new MultipleSimpleCollector(
                [
                    ['fileA', 'fileA'],
                    ['fileB', 'fileB'],
                ],
            )
        );

        $resources = $this->composer->compose($applications, 'application');

        self::assertEquals(
            [
                [
                    'id'    => $applicationId,
                    'fileA' => $fileA,
                    'fileB' => $fileB,
                ]
            ],
            $resources,
        );
    }
}
