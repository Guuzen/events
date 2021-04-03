<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\EachAuthorHasManyPosts;

use App\Infrastructure\ResComposer\Promise;
use App\Infrastructure\ResComposer\Tests\TestPromiseGroupResolver;

final class AuthorHasPosts extends TestPromiseGroupResolver
{
    public static function collectPromises(object $resource): array
    {
        return [
            Promise::withProperties('id', 'posts', $resource),
        ];
    }
}
