<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\WriterHasNoBooks;

use App\Infrastructure\ResComposer\Link\OneToMany;
use App\Infrastructure\ResComposer\Promise;
use App\Infrastructure\ResComposer\ResourceResolver;
use App\Infrastructure\ResComposer\Tests\StubResourceDataLoader;
use App\Infrastructure\ResComposer\Tests\TestCase;

final class WriterHasNoBooksTest extends TestCase
{
    public function test(): void
    {
        $writerId = '10';
        $writer   = [
            'id' => $writerId,
        ];

        $this->composer->registerLoader(new StubResourceDataLoader([]));
        $this->composer->registerResolver(
            new ResourceResolver(
                Writer::class,
                new OneToMany('writerId'),
                Book::class,
                StubResourceDataLoader::class,
                fn(Writer $writer) => [
                    new Promise(
                        fn(Writer $writer) => $writer->id,
                        /** @param Book[] $books */
                        fn(Writer $writer, array $books) => $writer->books = $books,
                        $writer,
                    ),
                ],
            )
        );

        /** @var Writer $resources */
        $resources = $this->composer->composeOne($writer, Writer::class);

        self::assertEquals([], $resources->books);
    }
}
