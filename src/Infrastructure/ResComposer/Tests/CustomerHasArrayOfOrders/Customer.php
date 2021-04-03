<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\CustomerHasArrayOfOrders;

use App\Infrastructure\ResComposer\Resource;

final class Customer implements Resource
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string[]
     */
    public $ordersIds;

    /**
     * @var Order[]
     */
    public $orders;

    /**
     * @param string[] $ordersIds
     */
    public function __construct(string $id, array $ordersIds)
    {
        $this->id        = $id;
        $this->ordersIds = $ordersIds;
    }

    public static function resolvers(): array
    {
        return [CustomerHasOrders::class];
    }
}
