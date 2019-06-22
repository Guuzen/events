<?php
declare(strict_types=1);

namespace App\Product\Model;

use App\Event\Model\EventId;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Promocode\Model\PromocodeId;
use App\Tariff\Model\TariffId;
use App\User\Model\User;
use DateTimeImmutable;
use Money\Money;

interface Product
{
    public function reserve(): void;

    public function cancelReserve(): void;

    public function makeOrder(
        OrderId $orderId,
        EventId $eventId,
        TariffId $tariffId,
        ?PromocodeId $promocodeId,
        User $user,
        Money $sum,
        DateTimeImmutable $makedAt
    ): Order;
}
