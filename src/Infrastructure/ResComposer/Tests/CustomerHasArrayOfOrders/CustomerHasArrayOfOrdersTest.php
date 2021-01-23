<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\CustomerHasArrayOfOrders;

use App\Infrastructure\ResComposer\Link\OneToOne;
use App\Infrastructure\ResComposer\Tests\StubResourceDataLoader;
use App\Infrastructure\ResComposer\Tests\TestCase;

final class CustomerHasArrayOfOrdersTest extends TestCase
{
    public function test(): void
    {
        $orderId1    = '1';
        $orderPrice1 = '111';
        $order1      = ['id' => $orderId1, 'price' => $orderPrice1];
        $orderId2    = '2';
        $orderPrice2 = '222';
        $order2      = ['id' => $orderId2, 'price' => $orderPrice2];

        $cutomerId = '1';
        $customer  = [
            'id'        => $cutomerId,
            'ordersIds' => [
                $orderId1,
                $orderId2,
            ],
        ];

        $this->composer->addResolver(
            new CustomerHasOrders(
                new StubResourceDataLoader([$order1, $order2]),
                new OneToOne('id'),
                Order::class
            )
        );

        /** @var Customer $resource */
        $resource = $this->composer->composeOne($customer, Customer::class);

        self::assertEquals(
            [
                new Order($orderId1, $orderPrice1),
                new Order($orderId2, $orderPrice2),
            ],
            $resource->orders
        );
    }
}
