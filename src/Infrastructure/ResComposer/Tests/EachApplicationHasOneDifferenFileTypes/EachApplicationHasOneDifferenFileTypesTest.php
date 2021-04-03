<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\EachApplicationHasOneDifferenFileTypes;

use App\Infrastructure\ResComposer\Link\OneToOne;
use App\Infrastructure\ResComposer\Tests\StubResourceDataLoader;
use App\Infrastructure\ResComposer\Tests\TestCase;

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

        $application  = [
            'id'    => '10',
            'fileA' => $fileAId,
            'fileB' => $fileBId,
        ];
        $applications = [$application];

        $this->composer->addResolver(
            new ApplicationHasFiles(
                new StubResourceDataLoader([$fileA, $fileB]),
                new OneToOne('id'),
                File::class
            )
        );

        /** @var Application[] $resources */
        $resources = $this->composer->compose($applications, Application::class);

        self::assertEquals(
            [
                new File($fileAId),
                new File($fileBId),
            ],
            [
                $resources[0]->fileA,
                $resources[0]->fileB,
            ]
        );
    }
}
