<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\EachAuthorInfoHasNoLinks;

use App\Infrastructure\ResComposer\Resource;

final class AuthorInfo implements Resource
{
    public $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function resolvers(): array
    {
        return [];
    }
}
