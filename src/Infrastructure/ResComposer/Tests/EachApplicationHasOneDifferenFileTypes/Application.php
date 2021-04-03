<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\EachApplicationHasOneDifferenFileTypes;

use App\Infrastructure\ResComposer\Promise;
use App\Infrastructure\ResComposer\Resource;
use App\Infrastructure\ResComposer\Tests\TestPromiseGroupResolver;

final class Application implements Resource
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var File
     */
    public $fileA;

    /**
     * @var string
     */
    public $fileAId;

    /**
     * @var File
     */
    public $fileB;

    /**
     * @var string
     */
    public $fileBId;

    public function __construct(string $id, string $fileA, string $fileB)
    {
        $this->id      = $id;
        $this->fileAId = $fileA;
        $this->fileBId = $fileB;
    }

    public static function resolvers(): array
    {
        return [ApplicationHasFiles::class];
    }
}
