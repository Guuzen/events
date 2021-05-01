<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\CustomerHasOrdersWhichHasDetails;

use App\Infrastructure\ResComposer\Link\OneToMany;
use App\Infrastructure\ResComposer\Link\OneToOne;
use App\Infrastructure\ResComposer\PromiseCollector\SimpleCollector;
use App\Infrastructure\ResComposer\Config\MainResource;
use App\Infrastructure\ResComposer\Config\RelatedResource;
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

        $this->composer->registerRelation(
            new MainResource('customer', new SimpleCollector('id', 'orders')),
            new OneToMany(),
            new RelatedResource('order', 'customerId', new OrdersLoader([$order])),
        );
        $this->composer->registerRelation(
            new MainResource('order', new SimpleCollector('id', 'details')),
            new OneToOne(),
            new RelatedResource('orderDetails', 'id', new OrderDetailsLoader([$orderDetails])),
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
