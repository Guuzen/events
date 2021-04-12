<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\CustomerHasArrayOfOrders;

use App\Infrastructure\ResComposer\Link\OneToOne;
use App\Infrastructure\ResComposer\Promise;
use App\Infrastructure\ResComposer\ResourceResolver;
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

        $this->composer->registerLoader(new StubResourceDataLoader([$order1, $order2]));
        $this->composer->registerResolver(
            new ResourceResolver(
                Customer::class,
                new OneToOne('id'),
                Order::class,
                StubResourceDataLoader::class,
                function (Customer $customer) {
                    $promises = [];
                    foreach ($customer->ordersIds as $index => $orderId) {
                        /** @psalm-suppress MissingClosureReturnType */
                        $idExtractor = fn() => $orderId;
                        $writer      = function (Customer $customer, Order $value) use ($index): void {
                            $customer->orders[$index] = $value;
                        };
                        $promises[]  = new Promise($idExtractor, $writer, $customer);
                    }

                    return $promises;
                },
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
