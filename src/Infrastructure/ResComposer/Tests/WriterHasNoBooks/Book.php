<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\WriterHasNoBooks;

use App\Infrastructure\ResComposer\Resource;

final class Book implements Resource
{
    public $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}
