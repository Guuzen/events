<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\EachHeadOfDepartmentInCompanyHasOneUserInfo;

final class Department
{
    public $head;

    public function __construct(User $head)
    {
        $this->head = $head;
    }
}
