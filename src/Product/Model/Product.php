<?php

namespace App\Product\Model;

use App\Event\Model\EventId;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Product\Model\Exception\OrderProductMustBeRelatedToEvent;
use App\Product\Model\Exception\ProductReserveCantBeCancelledIfAlreadyDelivered;
use App\Product\Model\Exception\ProductCantBeDeliveredIfNotReserved;
use App\Product\Model\Exception\ProductCantBeReservedIfAlreadyReserved;
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
    public function reserve(): void
    {
        if (true === $this->reserved) {
            throw new ProductCantBeReservedIfAlreadyReserved();
        }

        $this->reserved = true;
    }

    public function cancelReserve(): void
    {
        if (true === $this->delivered) {
            throw new ProductReserveCantBeCancelledIfAlreadyDelivered();
        }

        $this->reserved = false;
    }

    public function delivered(DateTimeImmutable $deliveredAt): void
    {
        if (false === $this->reserved) {
            throw new ProductCantBeDeliveredIfNotReserved();
        }

        $this->delivered   = true;
        $this->deliveredAt = $deliveredAt;
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
