<?php
declare(strict_types=1);

namespace App\Promocode\Model;

use App\Event\Model\EventId;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Product\Model\Product;
use App\Tariff\Model\Tariff;
use App\User\Model\User;
use DateTimeImmutable;
use Exception;
use Money\Money;

final class NullPromocode implements Promocode
{
    public function use(OrderId $orderId, Tariff $tariff, DateTimeImmutable $asOf): void
    {
    }

    public function cancel(OrderId $orderId): void
    {
        throw new Exception('Null promocode leaking abstraction');
    }

    public function makeUsable(): void
    {
        throw new Exception('Null promocode leaking abstraction');
    }

    public function makeNotUsable(): void
    {
        throw new Exception('Null promocode leaking abstraction');
    }

    public function makeOrder(
        OrderId $orderId,
        EventId $eventId,
        Tariff $tariff,
        User $user,
        Product $product,
        Money $sum,
        DateTimeImmutable $makedAt
    ): Order
    {
        return $tariff->makeOrder($orderId, $eventId, null, $product, $user, $sum, $makedAt);
    }

    public function apply(Money $price): Money
    {
        return $price;
    }
}
