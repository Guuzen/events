<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\CustomerHasOrdersWhichHasDetails;

use App\Infrastructure\ResComposer\Promise;
use App\Infrastructure\ResComposer\Resource;
use App\Infrastructure\ResComposer\Tests\TestPromiseGroupResolver;

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

    public function promises(): array
    {
        return [Promise::withProperties('id', 'orders', $this, TestPromiseGroupResolver::class)];
    }
}
