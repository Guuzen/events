<?php

namespace App\Product\Model;

use App\Event\Model\EventId;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Product\Model\Exception\OrderProductMustBeRelatedToEvent;
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

    public function __construct(
        ProductId $id,
        EventId $eventId,
        ProductType $type,
        DateTimeImmutable $createdAt,
        bool $reserved = false
    ) {
        $this->id        = $id;
        $this->eventId   = $eventId;
        $this->type      = $type;
        $this->createdAt = $createdAt;
        $this->reserved  = $reserved;
    }

    public function reserve(): void
    {
        $this->reserved = true;
    }

    public function cancelReserve(): void
    {
        $this->reserved = false;
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
