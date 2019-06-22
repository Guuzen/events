<?php
declare(strict_types=1);

namespace App\Promocode\Model;

use App\Event\Model\EventId;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Product\Model\Product;
use App\Promocode\Model\Discount\Discount;
use App\Tariff\Model\Tariff;
use App\User\Model\User;
use DateTimeImmutable;
use Money\Money;

interface Promocode extends Discount
{
    public function use(OrderId $orderId, Tariff $tariff, DateTimeImmutable $asOf): void;

    public function cancel(OrderId $orderId): void;

    public function makeUsable(): void;

    public function makeNotUsable(): void;

    public function makeOrder(
        OrderId $orderId,
        EventId $eventId,
        Tariff $tariff,
        User $user,
        Product $product,
        Money $sum,
        DateTimeImmutable $makedAt
    ): Order;
}
