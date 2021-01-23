<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\EachAuthorHasManyPosts;

use App\Infrastructure\ResComposer\Promise;
use App\Infrastructure\ResComposer\Resource;
use App\Infrastructure\ResComposer\Tests\TestPromiseGroupResolver;

final class Author implements Resource
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var Post[]
     */
    public $posts;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function promises(): array
    {
        return [
            Promise::withProperties('id', 'posts', $this, TestPromiseGroupResolver::class),
        ];
    }
}
