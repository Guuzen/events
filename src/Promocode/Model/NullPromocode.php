<?php



namespace App\Promocode\Model;

use App\Order\Model\OrderId;
use App\Tariff\Model\Tariff;
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

    public function apply(Money $price): Money
    {
        return $price;
    }
}
