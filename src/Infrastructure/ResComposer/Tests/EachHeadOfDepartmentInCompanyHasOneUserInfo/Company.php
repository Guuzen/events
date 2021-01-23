<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\EachHeadOfDepartmentInCompanyHasOneUserInfo;

use App\Infrastructure\ResComposer\Promise;
use App\Infrastructure\ResComposer\Resource;
use App\Infrastructure\ResComposer\Tests\TestPromiseGroupResolver;

final class Company implements Resource
{
    /**
     * @var  Department[]
     */
    public $departments;

    /**
     * @param Department[] $departments
     */
    public function __construct(array $departments)
    {
        $this->departments = $departments;
    }

    public function promises(): array
    {
        $promises = [];
        foreach ($this->departments as $department) {
            $promises[] = Promise::withProperties('id', 'userInfo', $department->head, TestPromiseGroupResolver::class);
        }

        return $promises;
    }
}
