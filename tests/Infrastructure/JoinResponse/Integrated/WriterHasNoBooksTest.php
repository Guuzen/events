<?php

declare(strict_types=1);

namespace Tests\Infrastructure\JoinResponse\Integrated;

use App\Infrastructure\ResponseComposer\ResourceProviders;
use App\Infrastructure\ResponseComposer\Schema;
use App\Infrastructure\ResponseComposer\SchemaProvider;
use PHPUnit\Framework\TestCase;

final class WriterHasNoBooksTest extends TestCase
{
    public function test(): void
    {
        $writer  = [
            'id'     => 'nonsense',
            'bookId' => '10',
        ];
        $writers = [
            $writer,
        ];

        $schema    = Writer::schema();
        $providers = new ResourceProviders(
            [
                Writer::class => new StubResourceProvider($writers),
                Book::class   => new StubResourceProvider([]),
            ]
        );

        $groupBuilder = $schema->collect($writers, $providers);
        $resonses     = $groupBuilder->build();

        self::assertEquals([new Writer($writer, [])], $resonses);
    }
}

final class Writer implements SchemaProvider
{
    private array $writer;
    private array $books;

    public function __construct(array $writer, array $books)
    {
        $this->writer = $writer;
        $this->books  = $books;
    }

    public static function schema(): Schema
    {
        $schema = new Schema(self::class);
        $schema->oneToMany(
            Book::schema(),
            fn(array $writer) => (string)$writer['bookId'],
            fn(array $book) => (string)$book['id'],
        );

        return $schema;
    }
}

final class Book implements SchemaProvider
{
    private array $book;

    public function __construct(array $book)
    {
        $this->book = $book;
    }

    public static function schema(): Schema
    {
        return new Schema(self::class);
    }
}
