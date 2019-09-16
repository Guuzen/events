<?php

namespace App\Product\Model;

use App\Common\Error;
use App\Event\Model\EventId;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Product\Service\ProductEmailDelivery;
use App\Product\Model\Error\OrderAndProductMustBeRelatedToSameEvent;
use App\Product\Model\Error\OrderAndProductMustBeRelatedToSameTariff;
use App\Product\Model\Error\ProductCantBeDeliveredIfNotReserved;
use App\Product\Model\Error\ProductCantBeReservedIfAlreadyReserved;
use App\Product\Model\Exception\ProductReserveCantBeCancelledIfAlreadyDelivered;
use App\Product\Service\Error\ProductNotDelivered;
use App\Product\Service\Error\ProductEmailNotFound;
use App\Tariff\Model\TariffId;
use App\User\Model\User;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Money\Money;

/**
 * @ORM\Entity
 */
class Product
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
    ) {
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
     * @return ProductCantBeDeliveredIfNotReserved|ProductNotDelivered|ProductEmailNotFound|null
     */
    public function deliver(ProductEmailDelivery $productEmailDelivery, DateTimeImmutable $deliveredAt)
    {
        if (!$this->reserved) {
            return new ProductCantBeDeliveredIfNotReserved();
        }

        $deliveryError = $productEmailDelivery->deliver($this->id, $this->type);
        if ($deliveryError instanceof Error) {
            return $deliveryError;
        }

        $this->delivered   = true;
        $this->deliveredAt = $deliveredAt;

        return null;
    }

    /**
     * @return Order|OrderAndProductMustBeRelatedToSameEvent|OrderAndProductMustBeRelatedToSameTariff
     */
    public function makeOrder(
        OrderId $orderId,
        EventId $eventId,
        TariffId $tariffId,
        Money $sum,
        User $user,
        DateTimeImmutable $asOf
    ) {
        if (!$this->eventId->equals($eventId)) {
            return new OrderAndProductMustBeRelatedToSameEvent();
        }
        if (!$this->tariffId->equals($tariffId)) {
            return new OrderAndProductMustBeRelatedToSameTariff();
        }

        return $user->makeOrder($orderId, $eventId, $this->id, $tariffId, $sum, $asOf);
    }
}
