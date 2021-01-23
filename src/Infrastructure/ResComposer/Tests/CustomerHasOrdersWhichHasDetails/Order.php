<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\CustomerHasOrdersWhichHasDetails;

use App\Infrastructure\ResComposer\Promise;
use App\Infrastructure\ResComposer\Resource;
use App\Infrastructure\ResComposer\Tests\SecondTestPromiseGroupResolver;

final class Order implements Resource
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $customerId;

    /**
     * @var OrderDetails
     */
    public $details;

    public function __construct(string $id, string $customerId)
    {
        $this->id         = $id;
        $this->customerId = $customerId;
    }

    public function promises(): array
    {
        return [Promise::withProperties('id', 'details', $this, SecondTestPromiseGroupResolver::class)];
    }
}

