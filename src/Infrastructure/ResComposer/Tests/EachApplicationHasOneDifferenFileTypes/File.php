<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\EachApplicationHasOneDifferenFileTypes;

use App\Infrastructure\ResComposer\Resource;

final class File implements Resource
{
    /**
     * @var string
     */
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
