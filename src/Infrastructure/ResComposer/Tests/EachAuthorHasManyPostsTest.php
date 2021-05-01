<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests;

use App\Infrastructure\ResComposer\Link\OneToMany;
use App\Infrastructure\ResComposer\PromiseCollector\SimpleCollector;
use App\Infrastructure\ResComposer\Config\MainResource;
use App\Infrastructure\ResComposer\Config\RelatedResource;

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

        $this->composer->registerRelation(
            new MainResource('author', new SimpleCollector('id', 'posts')),
            new OneToMany(),
            new RelatedResource('post', 'authorId', new StubResourceDataLoader($posts))
        );

        $resources = $this->composer->compose($authors, 'author');

        self::assertEquals(
            [
                [
                    'id'    => $authorId1,
                    'posts' => [$post1],
                ],
                [
                    'id'    => $authorId2,
                    'posts' => [$post2],
                ],
            ],
            $resources,
        );
    }
}
