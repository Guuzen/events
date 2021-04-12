<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\CustomerHasOrdersWhichHasDetails;

use App\Infrastructure\ResComposer\Link\OneToMany;
use App\Infrastructure\ResComposer\Link\OneToOne;
use App\Infrastructure\ResComposer\Promise;
use App\Infrastructure\ResComposer\ResourceResolver;
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

        $this->composer->registerLoader(new OrdersLoader([$order]));
        $this->composer->registerResolver(
            new ResourceResolver(
                Customer::class,
                new OneToMany('customerId'),
                Order::class,
                OrdersLoader::class,
                fn (Customer $customer) => [
                    new Promise(
                        fn (Customer $customer) => $customer->id,
                        /** @param Order[] $orders */
                        fn (Customer $customer, array $orders) => $customer->orders = $orders,
                        $customer
                    ),
                ],
            )
        );
        $this->composer->registerLoader(new OrderDetailsLoader([$orderDetails]));
        $this->composer->registerResolver(
            new ResourceResolver(
                Order::class,
                new OneToOne('id'),
                OrderDetails::class,
                OrderDetailsLoader::class,
                fn (Order $order) => [
                    new Promise(
                        fn (Order $order) => $order->id,
                        fn (Order $order, OrderDetails $orderDetails) => $order->details = $orderDetails,
                        $order,
                    ),
                ],
            )
        );

        /** @var Customer $resource */
        $resource = $this->composer->composeOne($customer, Customer::class);

        self::assertEquals(new OrderDetails($orderId), $resource->orders[0]->details);
    }
}
