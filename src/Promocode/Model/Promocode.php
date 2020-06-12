<?php

namespace App\Promocode\Model;

use App\Order\Model\OrderId;
use App\Promocode\Model\Discount\Discount;
use App\Tariff\Model\TariffId;
use DateTimeImmutable;

// TODO скидка и промокод - может быть это 2 разных контекста ?
interface Promocode extends Discount
{
    // TODO order instead of orderId
    public function use(OrderId $orderId, TariffId $tariffId, DateTimeImmutable $asOf): void;

    public function cancel(OrderId $orderId): void;

    // TODO эти 2 похожи на отдельный интерфейс
    public function makeUsable(): void;

    public function makeNotUsable(): void;
}
