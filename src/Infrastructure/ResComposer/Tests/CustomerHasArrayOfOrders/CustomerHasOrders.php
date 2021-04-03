<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\CustomerHasArrayOfOrders;

use App\Infrastructure\ResComposer\Promise;
use App\Infrastructure\ResComposer\Tests\TestPromiseGroupResolver;

final class CustomerHasOrders extends TestPromiseGroupResolver
{
    public static function collectPromises(object $resource): array
    {
        /** @var Customer $resource */
        $promises = [];

        foreach ($resource->ordersIds as $index => $orderId) {
            $idExtractor = fn(): string => $orderId;
            $writer      = function (Customer $customer, Order $value) use ($index): void {
                $customer->orders[$index] = $value;
            };
            $promises[]  = new Promise($idExtractor, $writer, $resource);
        }

        return $promises;
    }
}
