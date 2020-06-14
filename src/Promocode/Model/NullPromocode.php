<?php

namespace App\Promocode\Model;

use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Tariff\Model\TariffId;
use DateTimeImmutable;
use Exception;

final class NullPromocode implements Promocode
{
    public function use(OrderId $orderId, TariffId $tariffId, DateTimeImmutable $asOf): void
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

    public function applyDiscountToOrder(Order $order): void
    {
    }
}
