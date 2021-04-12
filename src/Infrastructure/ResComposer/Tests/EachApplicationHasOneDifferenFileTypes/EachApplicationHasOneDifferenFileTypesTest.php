<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\EachApplicationHasOneDifferenFileTypes;

use App\Infrastructure\ResComposer\Link\OneToOne;
use App\Infrastructure\ResComposer\Promise;
use App\Infrastructure\ResComposer\ResourceResolver;
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

        $this->composer->registerLoader(new StubResourceDataLoader([$fileA, $fileB]));
        $this->composer->registerResolver(
            new ResourceResolver(
                Application::class,
                new OneToOne('id'),
                File::class,
                StubResourceDataLoader::class,
                fn (Application $application) => [
                    new Promise(
                        fn (Application $application) => $application->fileAId,
                        fn (Application $application, File $file) => $application->fileA = $file,
                        $application,
                    ),
                    new Promise(
                        fn (Application $application) => $application->fileBId,
                        fn (Application $application, File $file) => $application->fileB = $file,
                        $application,
                    )
                ],
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
