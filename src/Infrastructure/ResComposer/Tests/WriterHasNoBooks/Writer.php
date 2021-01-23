<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\WriterHasNoBooks;

use App\Infrastructure\ResComposer\Promise;
use App\Infrastructure\ResComposer\Resource;
use App\Infrastructure\ResComposer\Tests\TestPromiseGroupResolver;

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

    public function promises(): array
    {
        return [Promise::withProperties('id', 'books', $this, TestPromiseGroupResolver::class)];
    }
}

