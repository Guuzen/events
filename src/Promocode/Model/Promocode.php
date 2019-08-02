<?php
declare(strict_types=1);

namespace App\Promocode\Model;

use App\Event\Model\EventId;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Product\Model\ProductId;
use App\Promocode\Model\Discount\Discount;
use App\Tariff\Model\TicketTariff;
use App\Tariff\Model\TicketTariffId;
use App\User\Model\User;
use DateTimeImmutable;
use Money\Money;

interface Promocode extends Discount
{
    public function use(OrderId $orderId, TicketTariff $tariff, DateTimeImmutable $asOf): void;

    public function cancel(OrderId $orderId): void;

    // TODO эти 2 похожи на отдельный интерфейс
    public function makeUsable(): void;

    public function makeNotUsable(): void;

    // TODO нужен ли в Order TariffId ?
    public function makeOrder(
        OrderId $orderId,
        EventId $eventId,
        ProductId $productId,
        TicketTariffId $tariffId,
        User $user,
        Money $sum,
        DateTimeImmutable $asOf
    ): Order;
}
