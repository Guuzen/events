<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests;

use App\Infrastructure\ResComposer\Link\OneToMany;
use App\Infrastructure\ResComposer\PromiseCollector\SimpleCollector;

final class WriterHasNoBooksTest extends TestCase
{
    public function test(): void
    {
        $writerId = '10';
        $writer   = [
            'id' => $writerId,
        ];

        $this->composer->registerConfig(
            'writer',
            new OneToMany('writerId'),
            'book',
            new StubResourceDataLoader([]),
            new SimpleCollector('id', 'books'),
        );

        $resources = $this->composer->composeOne($writer, 'writer');

        self::assertEquals(['id' => $writerId, 'books' => []], $resources);
    }
}
