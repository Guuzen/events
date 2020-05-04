<?php

namespace App\Product\Model;

use App\Common\Error;
use App\Event\Model\EventId;
use App\Infrastructure\DomainEvent\Entity;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Product\Model\Error\OrderAndProductMustBeRelatedToSameEvent;
use App\Product\Model\Error\OrderAndProductMustBeRelatedToSameTariff;
use App\Product\Model\Error\ProductCantBeDeliveredIfNotReserved;
use App\Product\Model\Error\ProductCantBeReservedIfAlreadyReserved;
use App\Product\Model\Exception\ProductReserveCantBeCancelledIfAlreadyDelivered;
use App\Tariff\Model\TariffId;
use App\User\Model\UserId;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Money\Money;

/**
 * @ORM\Entity
 */
class Product extends Entity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="app_product_id")
     */
    private $id;

    /**
     * @ORM\Column(type="app_event_id")
     */
    private $eventId;

    /**
     * @ORM\Column(type="app_tariff_id")
     */
    private $tariffId;

    /**
     * @ORM\Column(type="boolean")
     */
    private $reserved;

    /**
     * @ORM\Column(type="app_product_type")
     */
    private $type;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $delivered;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $deliveredAt;

    public function __construct(
        ProductId $id,
        EventId $eventId,
        TariffId $tariffId,
        ProductType $type,
        DateTimeImmutable $createdAt,
        bool $reserved = false,
        bool $delivered = false
    )
    {
        $this->id        = $id;
        $this->eventId   = $eventId;
        $this->tariffId  = $tariffId;
        $this->createdAt = $createdAt;
        $this->reserved  = $reserved;
        $this->delivered = $delivered;
        $this->type      = $type;
    }

    // TODO reserved at
    public function reserve(): ?ProductCantBeReservedIfAlreadyReserved
    {
        if ($this->reserved) {
            return new ProductCantBeReservedIfAlreadyReserved();
        }

        $this->reserved = true;

        return null;
    }

    public function cancelReserve(): void
    {
        if ($this->delivered) {
            throw new ProductReserveCantBeCancelledIfAlreadyDelivered();
        }

        $this->reserved = false;
    }

    /**
     * @return Error|null
     */
    public function deliver(DateTimeImmutable $deliveredAt)
    {
        if (!$this->reserved) {
            return new ProductCantBeDeliveredIfNotReserved();
        }

        $this->delivered   = true;
        $this->deliveredAt = $deliveredAt;

        $this->rememberThat(new ProductDelivered($this->id, $this->type));

        return null;
    }

    /**
     * @return Order|OrderAndProductMustBeRelatedToSameEvent|OrderAndProductMustBeRelatedToSameTariff
     */
    public function makeOrder(
        OrderId $orderId,
        EventId $eventId,
        TariffId $tariffId,
        UserId $userId,
        Money $sum,
        DateTimeImmutable $asOf
    )
    {
        if (!$this->eventId->equals($eventId)) {
            return new OrderAndProductMustBeRelatedToSameEvent();
        }
        if (!$this->tariffId->equals($tariffId)) {
            return new OrderAndProductMustBeRelatedToSameTariff();
        }

        return new Order($orderId, $eventId, $this->id, $tariffId, $userId, $sum, $asOf);
    }
}
