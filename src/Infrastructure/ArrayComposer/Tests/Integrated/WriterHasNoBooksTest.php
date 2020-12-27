<?php

declare(strict_types=1);

namespace App\Infrastructure\ArrayComposer\Tests\Integrated;

use App\Infrastructure\ArrayComposer\Path;
use App\Infrastructure\ArrayComposer\ResourceProviders;
use App\Infrastructure\ArrayComposer\Schema;
use PHPUnit\Framework\TestCase;

final class WriterHasNoBooksTest extends TestCase
{
    public function test(): void
    {
        $writer  = [
            'id' => '10',
        ];
        $writers = [
            $writer,
        ];

        $schema = $schema = new Schema();
        $schema->oneToMany(
            'writerProvider',
            new Path(['id']),
            'bookProvider',
            new Path(['writer']),
            'books',
        );
        $providers = new ResourceProviders(
            [
                'writerProvider' => new StubResourceProvider($writers),
                'bookProvider'   => new StubResourceProvider([]),
            ]
        );

        $result = $schema->collect($writers, 'writerProvider', $providers);

        self::assertEquals(
            [
                ['id' => '10', 'books' => []]
            ],
            $result
        );
    }
}
