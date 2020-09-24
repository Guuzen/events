<?php

namespace App\Promocode\Model;

use App\Event\Model\EventId;
use App\Infrastructure\DomainEvent\Entity;
use App\Order\Model\OrderId;
use App\Promocode\Model\AllowedTariffs\AllowedTariffs;
use App\Promocode\Model\Discount\Discount;
use App\Promocode\Model\Exception\PromocodeExpired;
use App\Promocode\Model\Exception\PromocodeNotAllowedForTariff;
use App\Promocode\Model\Exception\PromocodeNotUsable;
use App\Promocode\Model\Exception\PromocodeNotUsedInOrder;
use App\Promocode\Model\Exception\PromocodeUseLimitExceeded;
use App\Tariff\Model\TariffId;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Promocode extends Entity
{
    /**
     * @var PromocodeId
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type=PromocodeId::class)
     */
    private $id;

    /**
     * @ORM\Column(type=EventId::class)
     *
     * @var EventId
     */
    private $eventId;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $code;

    /**
     * @ORM\Column(type=Discount::class)
     *
     * @var Discount
     */
    private $discount;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $useLimit;

    /**
     * @var DateTimeImmutable
     *
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $expireAt;

    /**
     * @ORM\Column(type=AllowedTariffs::class)
     *
     * @var AllowedTariffs
     */
    private $allowedTariffs;
    // TODO нельзя применять промокод к отменённому заказу

    /**
     * @ORM\Column(type=UsedInOrders::class)
     *
     * @var UsedInOrders
     */
    private $usedInOrders;
    // TODO State ?
    /**
     * @var bool
     *
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

    public function use(OrderId $orderId, TariffId $tariffId, DateTimeImmutable $asOf): void
    {
        if (!$this->usable) {
            throw new PromocodeNotUsable('');
        }

        if ($this->useLimitExceeded()) {
            throw new PromocodeUseLimitExceeded('');
        }

        if ($this->expired($asOf)) {
            throw new PromocodeExpired('');
        }

        if (!$this->allowedTariffs->contains($tariffId)) {
            throw new PromocodeNotAllowedForTariff('');
        }

        $this->usedInOrders = $this->usedInOrders->add($orderId);

        $this->rememberThat(
            new PromocodeUsed(
                $this->eventId,
                $orderId,
                $this->discount
            )
        );
    }

    private function useLimitExceeded(): bool
    {
        return $this->usedInOrders->count() === $this->useLimit;
    }

    private function expired(DateTimeImmutable $at): bool
    {
        return $this->expireAt < $at;
    }

    public function cancel(OrderId $orderId): void
    {
        if (!$this->usedInOrders($orderId)) {
            throw new PromocodeNotUsedInOrder('');
        }

        $this->usedInOrders = $this->usedInOrders->remove($orderId);
    }

    private function usedInOrders(OrderId $orderId): bool
    {
        return $this->usedInOrders->has($orderId);
    }

    public function makeUsable(): void
    {
        $this->usable = true;
    }

    public function makeNotUsable(): void
    {
        $this->usable = false;
    }
}
