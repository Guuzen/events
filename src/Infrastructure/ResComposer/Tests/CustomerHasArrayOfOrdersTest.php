<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests;

use App\Infrastructure\ResComposer\PromiseCollector\ArrayCollector;
use App\Infrastructure\ResComposer\Link\OneToOne;

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

        $this->composer->registerConfig(
            'customer',
            new OneToOne('id'),
            'order',
            new StubResourceDataLoader([$order1, $order2]),
            new ArrayCollector('ordersIds', 'orders'),
        );

        $resource = $this->composer->composeOne($customer, 'customer');

        self::assertEquals(
            [
                'id'        => $cutomerId,
                'ordersIds' => [
                    $orderId1,
                    $orderId2,
                ],
                'orders'    => [
                    $order1,
                    $order2
                ],
            ],
            $resource
        );
    }
}
