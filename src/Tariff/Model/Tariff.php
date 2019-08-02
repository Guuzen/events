<?php
declare(strict_types=1);

namespace App\Tariff\Model;

use App\Event\Model\EventId;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Product\Model\ProductId;
use App\Promocode\Model\AllowedTariffs\AllowedTariffs;
use App\Promocode\Model\Discount\Discount;
use App\Promocode\Model\Promocode;
use App\User\Model\User;
use DateTimeImmutable;
use Money\Money;

interface Tariff
{
    public function calculateSum(Discount $discount, DateTimeImmutable $asOf): ?Money;

    public function relatedToEvent(EventId $eventId): bool;

    public function allowedForUse(AllowedTariffs $allowedTariffs): bool;

    // TODO is this method from some other interface?
    public function makeOrder(
        OrderId $orderId,
        EventId $eventId,
        ProductId $productId,
        Promocode $promocode,
        User $user,
        DateTimeImmutable $asOf
    ): Order;
}
