<?php

namespace App\Promocode\Model;

use App\Event\Model\EventId;
use App\Order\Model\OrderId;
use App\Promocode\Model\AllowedTariffs\AllowedTariffs;
use App\Promocode\Model\Discount\Discount;
use App\Promocode\Model\Exception\PromocodeAlreadyUsedInOrder;
use App\Promocode\Model\Exception\PromocodeAndTariffRelatedToDifferentEvents;
use App\Promocode\Model\Exception\PromocodeNotAllowedForTariff;
use App\Promocode\Model\Exception\PromocodeNotUsable;
use App\Promocode\Model\Exception\PromocodeNotUsedInOrder;
use App\Promocode\Model\Exception\PromocodeUseLimitExceeded;
use App\Tariff\Model\Exception\PromocodeExpired;
use App\Tariff\Model\Tariff;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Money\Money;

/**
 * @ORM\Entity
 */
class RegularPromocode implements Promocode
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="app_promocode_id")
     */
    private $id;

    /**
     * @ORM\Column(type="app_event_id")
     *
     * @var EventId
     */
    private $eventId;

    /**
     * @ORM\Column(type="string")
     */
    private $code;

    /**
     * @ORM\Column(type="app_promocode_discount")
     *
     * @var Discount
     */
    private $discount;

    /**
     * @ORM\Column(type="integer")
     */
    private $useLimit;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $expireAt;

    /**
     * @ORM\Column(type="app_promocode_allowed_tariffs")
     *
     * @var AllowedTariffs
     */
    private $allowedTariffs;
    // TODO нельзя применять промокод к отменённому заказу

    /**
     * @ORM\Column(type="app_promocode_used_in_orders")
     *
     * @var UsedInOrders
     */
    private $usedInOrders;
    // TODO State ?
    /**
     * @ORM\Column(type="boolean")
     */
    private $usable;

    public function __construct(
        PromocodeId $id,
        EventId $eventId,
        string $code,
        Discount $discount,
        int $useLimit, // TODO primitive obsession ?
        DateTimeImmutable $expireAt,
        AllowedTariffs $allowedTariffs, // TODO tariffs MUST be for same event as promocode
        bool $usable = true // TODO зачем нужен этот флаг ?
    )
    {
        $this->id             = $id;
        $this->eventId        = $eventId;
        $this->code           = $code;
        $this->discount       = $discount;
        $this->useLimit       = $useLimit;
        $this->expireAt       = $expireAt;
        $this->allowedTariffs = $allowedTariffs;
        $this->usable         = $usable;
        $this->usedInOrders   = new UsedInOrders([]);
    }

    public function use(OrderId $orderId, Tariff $tariff, DateTimeImmutable $asOf): void
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

        $this->usedInOrders = $this->usedInOrders->add($orderId);
    }

    public function cancel(OrderId $orderId): void
    {
        if (!$this->usedInOrders($orderId)) {
            throw new PromocodeNotUsedInOrder();
        }

        $this->usedInOrders->remove($orderId);
    }

    public function makeUsable(): void
    {
        $this->usable = true;
    }

    public function makeNotUsable(): void
    {
        $this->usable = false;
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
        return $this->usedInOrders->count() === $this->useLimit;
    }

    private function usedInOrders(OrderId $orderId): bool
    {
        return $this->usedInOrders->has($orderId);
    }
}
