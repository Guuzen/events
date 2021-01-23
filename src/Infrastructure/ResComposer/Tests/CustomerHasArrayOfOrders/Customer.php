<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\CustomerHasArrayOfOrders;

use App\Infrastructure\ResComposer\Promise;
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

    public function promises(): array
    {
        $promises = [];

        foreach ($this->ordersIds as $index => $orderId) {
            $idExtractor = fn(): string => $orderId;
            $writer      = function (Customer $object, Order $value) use ($index): void {
                $object->orders[$index] = $value;
            };
            $promises[]  = new Promise($idExtractor, $writer, $this, CustomerHasOrders::class);
        }

        return $promises;
    }
}
