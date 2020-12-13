<?php

declare(strict_types=1);

namespace Tests\Infrastructure\JoinResponse\Integrated;

use App\Infrastructure\ArrayComposer\Path\Path;
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

        $schema = $schema = new Schema('writerProvider');
        $schema->oneToMany(
            new Schema('bookProvider'),
            new Path(['id']),
            new Path(['writer']),
            'books',
        );
        $providers = new ResourceProviders(
            [
                'writerProvider' => new StubResourceProvider($writers),
                'bookProvider'   => new StubResourceProvider([]),
            ]
        );

        $result = $schema->collect($writers, $providers);

        self::assertEquals(
            [
                ['id' => '10', 'books' => []]
            ],
            $result
        );
    }
}
