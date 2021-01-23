<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\CommentHasAuthorAndPost;

use App\Infrastructure\ResComposer\Link\OneToOne;
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

        $this->composer->addResolver(
            new CommentHasAuthor(
                new StubResourceDataLoader([$author]),
                new OneToOne('id'),
                Author::class,
            )
        );
        $this->composer->addResolver(
            new CommentHasPost(
                new StubResourceDataLoader([$post]),
                new OneToOne('id'),
                Post::class,
            )
        );

        /** @var Comment $resource */
        $resource = $this->composer->composeOne($comment, Comment::class);

        self::assertEquals(new Author($authorId, $commentId), $resource->author);
        self::assertEquals(new Post($postId, $commentId), $resource->post);
    }
}
