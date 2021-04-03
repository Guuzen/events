<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\EachApplicationHasOneDifferenFileTypes;

use App\Infrastructure\ResComposer\Promise;
use App\Infrastructure\ResComposer\Tests\TestPromiseGroupResolver;

final class ApplicationHasFiles extends TestPromiseGroupResolver
{
    public static function collectPromises(object $resource): array
    {
        return [
            Promise::withProperties('fileAId', 'fileA', $resource),
            Promise::withProperties('fileBId', 'fileB', $resource),
        ];
    }
}
