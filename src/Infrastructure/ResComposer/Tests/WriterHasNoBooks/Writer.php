<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\WriterHasNoBooks;

use App\Infrastructure\ResComposer\Resource;

final class Writer implements Resource
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var Book[]
     */
    public $books;

    /**
     * @param Book[] $books
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function resolvers(): array
    {
        return [WriterHasBooks::class];
    }
}

