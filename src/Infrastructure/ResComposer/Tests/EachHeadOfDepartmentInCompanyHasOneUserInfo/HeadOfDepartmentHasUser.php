<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\EachHeadOfDepartmentInCompanyHasOneUserInfo;

use App\Infrastructure\ResComposer\Promise;
use App\Infrastructure\ResComposer\Tests\TestPromiseGroupResolver;

final class HeadOfDepartmentHasUser extends TestPromiseGroupResolver
{
    public static function collectPromises(object $resource): array
    {
        /** @var Company $resource */
        $promises = [];
        foreach ($resource->departments as $department) {
            $promises[] = Promise::withProperties('id', 'userInfo', $department->head);
        }

        return $promises;
    }
}
