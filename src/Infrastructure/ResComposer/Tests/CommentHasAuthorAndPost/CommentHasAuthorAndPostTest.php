<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\CommentHasAuthorAndPost;

use App\Infrastructure\ResComposer\Link\OneToOne;
use App\Infrastructure\ResComposer\PromiseCollector\SimpleCollector;
use App\Infrastructure\ResComposer\Tests\TestCase;

final class CommentHasAuthorAndPostTest extends TestCase
{
    public function test(): void
    {
        $commentId = '1';
        $comment   = ['id' => $commentId];
        $authorId  = '1';
        $author    = [
            'id'        => $authorId,
            'commentId' => $commentId
        ];
        $postId    = '1';
        $post      = [
            'id'        => $postId,
            'commentId' => $commentId,
        ];

        $this->composer->registerLoader(new AuthorsLoader([$author]));
        $this->composer->registerConfig(
            'comment',
            new OneToOne('id'),
            'author',
            AuthorsLoader::class,
            new SimpleCollector('id', 'author'),
        );
        $this->composer->registerLoader(new PostsLoader([$post]));
        $this->composer->registerConfig(
            'comment',
            new OneToOne('id'),
            'post',
            PostsLoader::class,
            new SimpleCollector('id', 'post'),
        );

        $resource = $this->composer->composeOne($comment, 'comment');

        self::assertEquals(
            [
                'id'     => $commentId,
                'author' => $author,
                'post'   => $post,
            ],
            $resource,
        );
    }
}
