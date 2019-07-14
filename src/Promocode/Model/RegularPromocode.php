<?php

namespace App\Promocode\Model;

use App\Event\Model\EventId;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Order\Model\ProductId;
use App\Promocode\Model\Discount\Discount;
use App\Promocode\Model\Exception\OrderPromocodeMustBeRelatedToEvent;
use App\Promocode\Model\Exception\PromocodeAlreadyUsedInOrder;
use App\Promocode\Model\Exception\PromocodeAndTariffRelatedToDifferentEvents;
use App\Promocode\Model\Exception\PromocodeNotAllowedForTariff;
use App\Promocode\Model\Exception\PromocodeNotUsable;
use App\Promocode\Model\Exception\PromocodeNotUsedInOrder;
use App\Promocode\Model\Exception\PromocodeUseLimitExceeded;
use App\Promocode\Model\AllowedTariffs\AllowedTariffs;
use App\Tariff\Model\Exception\PromocodeExpired;
use App\Tariff\Model\TicketTariff;
use App\Tariff\Model\TicketTariffId;
use App\User\Model\User;
use DateTimeImmutable;
use Money\Money;

// TODO promocode code?!
final class RegularPromocode implements Promocode
{
    private $id;

    private $eventId;

    private $discount;

    private $useLimit;

    private $expireAt;

    private $allowedTariffs;

    /** @var OrderId[] */
    // TODO количество не может быть больше $useLimit
    // TODO не может быть больше 1 использования в заказе
    // TODO нельзя применять промокод к отменённому заказу
    private $usedInOrders;

    private $usable;

    public function __construct(
        PromocodeId $id,
        EventId $eventId,
        Discount $discount,
        int $useLimit,
        DateTimeImmutable $expireAt,
        AllowedTariffs $allowedTariffs,
        $usedInOrders = [],
        bool $usable = false
    ) {
        $this->id             = $id;
        $this->eventId        = $eventId;
        $this->discount       = $discount;
        $this->useLimit       = $useLimit;
        $this->expireAt       = $expireAt;
        $this->allowedTariffs = $allowedTariffs;
        $this->usedInOrders   = $usedInOrders;
        $this->usable         = $usable;
    }

    public function use(OrderId $orderId, TicketTariff $tariff, DateTimeImmutable $asOf): void
    {
        if (!$this->usable) {
            throw new PromocodeNotUsable();
        }

        if ($this->usedInOrders($orderId)) {
            throw new PromocodeAlreadyUsedInOrder();
        }

        if ($this->useLimitExceeded()) {
            throw new PromocodeUseLimitExceeded();
        }

        if ($this->expired($asOf)) {
            throw new PromocodeExpired();
        }

        if (!$tariff->relatedToEvent($this->eventId)) {
            throw new PromocodeAndTariffRelatedToDifferentEvents();
        }

        if (!$tariff->allowedForUse($this->allowedTariffs)) { // TODO сравнение id
            throw new PromocodeNotAllowedForTariff();
        }

        $this->usedInOrders[] = $orderId;
    }

    public function cancel(OrderId $orderId): void
    {
        if (!$this->usedInOrders($orderId)) {
            throw new PromocodeNotUsedInOrder();
        }

        $this->usedInOrders = array_diff($this->usedInOrders, [$orderId]);
    }

    public function makeUsable(): void
    {
        $this->usable = true;
    }

    public function makeNotUsable(): void
    {
        $this->usable = false;
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
        if (!$this->eventId->equals($eventId)) {
            throw new OrderPromocodeMustBeRelatedToEvent();
        }

        return $user->makeOrder($orderId, $eventId, $productId, $tariffId, $this->id, $sum, $asOf);
    }

    public function apply(Money $price): Money
    {
        return $this->discount->apply($price);
    }

//    private function relatedToEvent(TariffInterface $tariff): bool
//    {
//        return $tariff->relatedToEvent($this->eventId);
//    }
//
//    private function allowedForUse(TariffInterface $tariff): bool
//    {
//        return $tariff->allowedForUse($this->allowedTariffs);
//    }

    private function expired(DateTimeImmutable $at): bool
    {
        return $this->expireAt < $at;
    }

    private function useLimitExceeded(): bool
    {
        return count($this->usedInOrders) === $this->useLimit;
    }

    private function usedInOrders(OrderId $orderId): bool
    {
        return in_array($orderId, $this->usedInOrders, true);
    }
}
