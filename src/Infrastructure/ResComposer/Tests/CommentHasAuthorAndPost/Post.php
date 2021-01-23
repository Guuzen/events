<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\CommentHasAuthorAndPost;

use App\Infrastructure\ResComposer\Resource;

final class Post implements Resource
{
    public $id;

    public $commentId;

    public function __construct(string $id, string $commentId)
    {
        $this->id        = $id;
        $this->commentId = $commentId;
    }

    public function promises(): array
    {
        return [];
    }
}
