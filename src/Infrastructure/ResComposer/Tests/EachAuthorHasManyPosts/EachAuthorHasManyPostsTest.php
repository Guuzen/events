<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\EachAuthorHasManyPosts;

use App\Infrastructure\ResComposer\Link\OneToMany;
use App\Infrastructure\ResComposer\Tests\StubResourceDataLoader;
use App\Infrastructure\ResComposer\Tests\TestCase;
use App\Infrastructure\ResComposer\Tests\TestPromiseGroupResolver;

final class EachAuthorHasManyPostsTest extends TestCase
{
    public function test(): void
    {
        $authorId1 = '1';
        $authorId2 = '2';
        $author1   = ['id' => $authorId1];
        $author2   = ['id' => $authorId2];
        $authors   = [
            $author1,
            $author2,
        ];
        $postId1   = 'nonsense';
        $postId2   = 'nonsense';
        $post1     = ['id' => $postId1, 'authorId' => $authorId1];
        $post2     = ['id' => $postId2, 'authorId' => $authorId2];
        $posts     = [
            $post1,
            $post2,
        ];

        $this->composer->addResolver(
            new TestPromiseGroupResolver(
                new StubResourceDataLoader($posts),
                new OneToMany('authorId'),
                Post::class
            )
        );

        /** @var Author[] $resources */
        $resources = $this->composer->compose($authors, Author::class);

        self::assertEquals(
            [
                [new Post($postId1, $authorId1)],
                [new Post($postId2, $authorId2)],
            ],
            [$resources[0]->posts, $resources[1]->posts]
        );
    }
}
