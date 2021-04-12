<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\CommentHasAuthorAndPost;

use App\Infrastructure\ResComposer\Link\OneToOne;
use App\Infrastructure\ResComposer\Promise;
use App\Infrastructure\ResComposer\ResourceResolver;
use App\Infrastructure\ResComposer\Tests\StubResourceDataLoader;
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
        $this->composer->registerResolver(
            new ResourceResolver(
                Comment::class,
                new OneToOne('id'),
                Author::class,
                AuthorsLoader::class,
                fn (Comment $comment) => [
                    new Promise(
                        fn(Comment $comment) => $comment->id,
                        fn(Comment $comment, Author $author) => $comment->author = $author,
                        $comment
                    )
                ],
            )
        );
        $this->composer->registerLoader(new PostsLoader([$post]));
        $this->composer->registerResolver(
            new ResourceResolver(
                Comment::class,
                new OneToOne('id'),
                Post::class,
                PostsLoader::class,
                fn (Comment $comment) => [
                    new Promise(
                        fn(Comment $comment) => $comment->id,
                        fn(Comment $comment, Post $post) => $comment->post = $post,
                        $comment
                    ),
                ],
            )
        );

        /** @var Comment $resource */
        $resource = $this->composer->composeOne($comment, Comment::class);

        self::assertEquals(new Author($authorId, $commentId), $resource->author);
        self::assertEquals(new Post($postId, $commentId), $resource->post);
    }
}
