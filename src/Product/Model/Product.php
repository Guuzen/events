<?php

namespace App\Product\Model;

use App\Event\Model\EventId;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Product\Model\Error\ProductCantBeDeliveredIfNotReserved;
use App\Product\Model\Error\ProductCantBeReservedIfAlreadyReserved;
use App\Product\Model\Exception\OrderProductMustBeRelatedToEvent;
use App\Product\Model\Exception\ProductReserveCantBeCancelledIfAlreadyDelivered;
use App\Tariff\Model\Tariff;
use App\User\Model\User;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Money\Money;

/**
 * @ORM\Entity
 */
final class Product
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
     * @ORM\Column(type="app_product_type")
     */
    private $type;

    /**
     * @ORM\Column(type="boolean")
     */
    private $reserved;

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
        ProductType $type,
        DateTimeImmutable $createdAt,
        bool $reserved = false,
        bool $delivered = false
    ) {
        $this->id        = $id;
        $this->eventId   = $eventId;
        $this->type      = $type;
        $this->createdAt = $createdAt;
        $this->reserved  = $reserved;
        $this->delivered = $delivered;
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

    public function delivered(DateTimeImmutable $deliveredAt): ?ProductCantBeDeliveredIfNotReserved
    {
        if (!$this->reserved) {
            return new ProductCantBeDeliveredIfNotReserved();
        }

        $this->delivered   = true;
        $this->deliveredAt = $deliveredAt;

        return null;
    }

    public function makeOrder(
        OrderId $orderId,
        EventId $eventId,
        Tariff $tariff,
        Money $sum,
        User $user,
        DateTimeImmutable $asOf
    ): Order {
        // TODO replace with check product type ?
        if (!$this->eventId->equals($eventId)) {
            throw new OrderProductMustBeRelatedToEvent();
        }

        return $tariff->makeOrder($orderId, $eventId, $this->id, $sum, $user, $asOf);
    }
}
