<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\WriterHasNoBooks;

use App\Infrastructure\ResComposer\Link\OneToMany;
use App\Infrastructure\ResComposer\Tests\StubResourceDataLoader;
use App\Infrastructure\ResComposer\Tests\TestCase;
use App\Infrastructure\ResComposer\Tests\TestPromiseGroupResolver;

final class WriterHasNoBooksTest extends TestCase
{
    public function test(): void
    {
        $writerId = '10';
        $writer   = [
            'id' => $writerId,
        ];

        $this->composer->addResolver(
            new TestPromiseGroupResolver(
                new StubResourceDataLoader([]),
                new OneToMany('writerId'),
                Book::class
            )
        );

        /** @var Writer $resources */
        $resources = $this->composer->composeOne($writer, Writer::class);

        self::assertEquals([], $resources->books);
    }
}
