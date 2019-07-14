<?php

declare(strict_types=1);

namespace App\Product\Model;

use App\Event\Model\EventId;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Promocode\Model\Promocode;
use App\Tariff\Model\Tariff;
use App\User\Model\User;
use DateTimeImmutable;

interface Product
{
    public function reserve(): void;

    public function cancelReserve(): void;

    public function makeOrder(
        OrderId $orderId,
        EventId $eventId,
        Tariff $tariff,
        Promocode $promocode,
        User $user,
        DateTimeImmutable $asOf
    ): Order;
}
