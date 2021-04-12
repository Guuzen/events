<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\EachAuthorHasManyPosts;

use App\Infrastructure\ResComposer\Resource;

final class Post implements Resource
{
    public $id;
    public $authorId;

    public function __construct(string $id, string $authorId)
    {
        $this->id       = $id;
        $this->authorId = $authorId;
    }
}
