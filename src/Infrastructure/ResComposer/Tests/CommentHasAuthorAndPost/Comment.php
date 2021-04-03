<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\CommentHasAuthorAndPost;

use App\Infrastructure\ResComposer\Resource;

final class Comment implements Resource
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var Author
     */
    public $author;

    /**
     * @var Post
     */
    public $post;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function resolvers(): array
    {
        return [
            CommentHasAuthor::class,
            CommentHasPost::class,
        ];
    }
}
