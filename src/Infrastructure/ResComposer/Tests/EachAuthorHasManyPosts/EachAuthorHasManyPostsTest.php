<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\EachAuthorHasManyPosts;

use App\Infrastructure\ResComposer\Link\OneToMany;
use App\Infrastructure\ResComposer\Promise;
use App\Infrastructure\ResComposer\ResourceResolver;
use App\Infrastructure\ResComposer\Tests\StubResourceDataLoader;
use App\Infrastructure\ResComposer\Tests\TestCase;

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

        $this->composer->registerLoader(new StubResourceDataLoader($posts));
        $this->composer->registerResolver(
            new ResourceResolver(
                Author::class,
                new OneToMany('authorId'),
                Post::class,
                StubResourceDataLoader::class,
                fn (Author $author) => [
                    new Promise(
                        fn (Author $author) => $author->id,
                        /** @param Post[] $posts */
                        fn (Author $author, array $posts) => $author->posts = $posts,
                        $author,
                    ),
                ],
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
