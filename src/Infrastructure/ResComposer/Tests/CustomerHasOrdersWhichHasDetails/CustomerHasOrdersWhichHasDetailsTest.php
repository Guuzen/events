<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\CustomerHasOrdersWhichHasDetails;

use App\Infrastructure\ResComposer\Link\OneToMany;
use App\Infrastructure\ResComposer\Link\OneToOne;
use App\Infrastructure\ResComposer\Tests\SecondTestPromiseGroupResolver;
use App\Infrastructure\ResComposer\Tests\StubResourceDataLoader;
use App\Infrastructure\ResComposer\Tests\TestCase;
use App\Infrastructure\ResComposer\Tests\TestPromiseGroupResolver;

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

        $this->composer->addResolver(
            new TestPromiseGroupResolver(
                new StubResourceDataLoader([$order]),
                new OneToMany('customerId'),
                Order::class
            )
        );
        $this->composer->addResolver(
            new SecondTestPromiseGroupResolver(
                new StubResourceDataLoader([$orderDetails]),
                new OneToOne('id'),
                OrderDetails::class
            )
        );

        /** @var Customer $resource */
        $resource = $this->composer->composeOne($customer, Customer::class);

        self::assertEquals(new OrderDetails($orderId), $resource->orders[0]->details);
    }
}
