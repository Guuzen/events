<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\CustomerHasOrdersWhichHasDetails;

use App\Infrastructure\ResComposer\Resource;

final class Customer implements Resource
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var Order[]
     */
    public $orders;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}
