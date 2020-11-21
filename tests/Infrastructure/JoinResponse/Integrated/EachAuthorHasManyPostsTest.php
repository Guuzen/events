<?php

declare(strict_types=1);

namespace Tests\Infrastructure\JoinResponse\Integrated;

use App\Infrastructure\ResponseComposer\ResourceProviders;
use App\Infrastructure\ResponseComposer\Schema;
use App\Infrastructure\ResponseComposer\SchemaProvider;
use PHPUnit\Framework\TestCase;

final class EachAuthorHasManyPostsTest extends TestCase
{
    public function test(): void
    {
        $authorKey1 = '1';
        $authorKey2 = '2';
        $author1    = new AuthorResource($authorKey1);
        $author2    = new AuthorResource($authorKey2);
        $authors    = [
            $author1,
            $author2,
        ];
        $post1      = new PostResource('nonsense', $authorKey1);
        $post2      = new PostResource('nonsense', $authorKey2);
        $posts      = [
            $post1,
            $post2,
        ];
        $schema     = Author::schema();
        $providers  = new ResourceProviders(
            [
                Post::class => new StubResourceProvider($posts),
            ]
        );

        $groupBuilder = $schema->collect($authors, $providers);
        $result       = $groupBuilder->build();

        self::assertEquals(
            [
                new Author($author1, [new Post($post1)]),
                new Author($author2, [new Post($post2)]),
            ],
            $result
        );
    }
}

final class Author implements SchemaProvider
{
    private AuthorResource $author;
    private array $posts;

    public function __construct(AuthorResource $author, array $posts)
    {
        $this->author = $author;
        $this->posts  = $posts;
    }

    public static function schema(): Schema
    {
        $schema = new Schema(self::class);
        $schema->oneToMany(
            Post::schema(),
            fn(AuthorResource $author) => $author->id,
            fn(PostResource $post) => $post->authorId,
        );

        return $schema;
    }
}

/**
 * @psalm-immutable
 */
final class AuthorResource
{
    public string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}

final class Post implements SchemaProvider
{
    private PostResource $post;

    public function __construct(PostResource $post)
    {
        $this->post = $post;
    }

    public static function schema(): Schema
    {
        return new Schema(self::class);
    }
}

/**
 * @psalm-immutable
 */
final class PostResource
{
    public string $id;
    public string $authorId;

    public function __construct(string $id, string $authorId)
    {
        $this->id       = $id;
        $this->authorId = $authorId;
    }
}
