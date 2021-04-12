<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\EachHeadOfDepartmentInCompanyHasOneUserInfo;

use App\Infrastructure\ResComposer\Resource;

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
}
