<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\CommentHasAuthorAndPost;

use App\Infrastructure\ResComposer\Promise;
use App\Infrastructure\ResComposer\Tests\TestPromiseGroupResolver;

final class CommentHasPost extends TestPromiseGroupResolver
{
    /**
     * @param Comment $resource
     */
    public static function initPromises(object $resource): array
    {
        return [Promise::withProperties('id', 'post', $resource, self::class)];
    }
}
