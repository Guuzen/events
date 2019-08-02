<?php

declare(strict_types=1);

namespace App\Promocode\Model;

use App\Event\Model\EventId;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Product\Model\ProductId;
use App\Tariff\Model\TicketTariff;
use App\Tariff\Model\TicketTariffId;
use App\User\Model\User;
use DateTimeImmutable;
use Exception;
use Money\Money;

final class NullPromocode implements Promocode
{
    public function use(OrderId $orderId, TicketTariff $tariff, DateTimeImmutable $asOf): void
    {
    }

    public function cancel(OrderId $orderId): void
    {
        throw new Exception('Null promocode leaking abstraction or just fall silently?');
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
        ProductId $productId,
        TicketTariffId $tariffId,
        User $user,
        Money $sum,
        DateTimeImmutable $asOf
    ): Order {
        return $user->makeOrder($orderId, $eventId, $productId, $tariffId, null, $sum, $asOf);
    }

    public function apply(Money $price): Money
    {
        return $price;
    }
}
