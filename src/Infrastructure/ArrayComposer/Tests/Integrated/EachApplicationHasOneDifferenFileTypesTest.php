<?php

declare(strict_types=1);

namespace App\Infrastructure\ArrayComposer\Tests\Integrated;

use App\Infrastructure\ArrayComposer\Path;
use App\Infrastructure\ArrayComposer\ResourceProviders;
use App\Infrastructure\ArrayComposer\Schema;
use PHPUnit\Framework\TestCase;

final class EachApplicationHasOneDifferenFileTypesTest extends TestCase
{
    public function test(): void
    {
        $fileIdA = '1';
        $fileIdB = '2';

        $fileA = [
            'id'  => $fileIdA,
            'foo' => 'bar',
        ];

        $fileB = [
            'id'  => $fileIdB,
            'bar' => 'baz',
        ];

        $application = [
            'fileTypeA' => $fileIdA,
            'fileTypeB' => $fileIdB,
        ];

        $schema = new Schema();

        $schema->oneToOne('app', new Path(['fileTypeA']), 'file', new Path(['id']), 'fileTypeA',);

        $schema->oneToOne('app', new Path(['fileTypeB']), 'file', new Path(['id']), 'fileTypeB',);

        $resourceProviders = $this->createMock(ResourceProviders::class);
        $resourceProviders
            ->expects(self::once())
            ->method('provide')
            ->with(['1', '2'], 'file')
            ->willReturn([$fileA, $fileB]);

        $result = $schema->collect([$application], 'app', $resourceProviders);

        self::assertEquals(
            [
                [
                    'fileTypeA' => $fileA,
                    'fileTypeB' => $fileB,
                ],
            ],
            $result
        );
    }
}
