<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\EachAuthorHasManyPosts;

use App\Infrastructure\ResComposer\Resource;

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

    public static function resolvers(): array
    {
        return [AuthorHasPosts::class];
    }
}
