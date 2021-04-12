<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\CustomerHasOrdersWhichHasDetails;

use App\Infrastructure\ResComposer\Resource;

final class OrderDetails implements Resource
{
    public $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}
