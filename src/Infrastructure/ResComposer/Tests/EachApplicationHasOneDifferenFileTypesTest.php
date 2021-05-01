<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests;

use App\Infrastructure\ResComposer\Config\MainResource;
use App\Infrastructure\ResComposer\Config\RelatedResource;
use App\Infrastructure\ResComposer\Link\OneToOne;
use App\Infrastructure\ResComposer\PromiseCollector\MergeCollector;
use App\Infrastructure\ResComposer\PromiseCollector\SimpleCollector;

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

        $this->composer->registerRelation(
            new MainResource(
                'application',
                new MergeCollector(
                    [
                        new SimpleCollector('fileA', 'fileA'),
                        new SimpleCollector('fileB', 'fileB'),
                    ],
                ),
            ),
            new OneToOne(),
            new RelatedResource('file', 'id', new StubResourceDataLoader([$fileA, $fileB])),
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
