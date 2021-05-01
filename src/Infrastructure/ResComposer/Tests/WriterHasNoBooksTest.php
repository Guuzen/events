<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests;

use App\Infrastructure\ResComposer\Config\MainResource;
use App\Infrastructure\ResComposer\Config\RelatedResource;
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

        $this->composer->registerRelation(
            new MainResource('writer', new SimpleCollector('id', 'books')),
            new OneToMany(),
            new RelatedResource('book', 'writerId', new StubResourceDataLoader([])),
        );

        $resources = $this->composer->composeOne($writer, 'writer');

        self::assertEquals(['id' => $writerId, 'books' => []], $resources);
    }
}
