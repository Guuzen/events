<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\CustomerHasOrdersWhichHasDetails;

use App\Infrastructure\ResComposer\Promise;
use App\Infrastructure\ResComposer\Tests\TestPromiseGroupResolver;

final class OrderHasDetails extends TestPromiseGroupResolver
{
    public static function collectPromises(object $resource): array
    {
        return [Promise::withProperties('id', 'details', $resource)];
    }
}
