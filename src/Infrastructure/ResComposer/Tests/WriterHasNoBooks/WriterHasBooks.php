<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\WriterHasNoBooks;

use App\Infrastructure\ResComposer\Promise;
use App\Infrastructure\ResComposer\Tests\TestPromiseGroupResolver;

final class WriterHasBooks extends TestPromiseGroupResolver
{
    public static function collectPromises(object $resource): array
    {
        return [Promise::withProperties('id', 'books', $resource)];
    }
}
