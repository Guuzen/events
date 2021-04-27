<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\CustomerHasOrdersWhichHasDetails;

use App\Infrastructure\ResComposer\Link\OneToMany;
use App\Infrastructure\ResComposer\Link\OneToOne;
use App\Infrastructure\ResComposer\PromiseCollector\SimpleCollector;
use App\Infrastructure\ResComposer\Tests\TestCase;

final class CustomerHasOrdersWhichHasDetailsTest extends TestCase
{
    public function test(): void
    {
        $customerId   = '1';
        $customer     = [
            'id' => $customerId,
        ];
        $orderId      = '2';
        $order        = [
            'id'         => $orderId,
            'customerId' => $customerId,
        ];
        $orderDetails = [
            'id' => $orderId,
        ];

        $this->composer->registerConfig(
            'customer',
            new OneToMany('customerId'),
            'order',
            new OrdersLoader([$order]),
            new SimpleCollector('id', 'orders'),
        );
        $this->composer->registerConfig(
            'order',
            new OneToOne('id'),
            'orderDetails',
            new OrderDetailsLoader([$orderDetails]),
            new SimpleCollector('id', 'details'),
        );

        $resource = $this->composer->composeOne($customer, 'customer');

        self::assertEquals(
            [
                'id'     => $customerId,
                'orders' => [
                    [
                        'id'         => $orderId,
                        'customerId' => $customerId,
                        'details'    => $orderDetails,
                    ]
                ],

            ],
            $resource,
        );
    }
}
