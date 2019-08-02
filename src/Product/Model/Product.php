<?php

namespace App\Product\Model;

use App\Event\Model\EventId;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Product\Model\Exception\OrderProductMustBeRelatedToEvent;
use App\Promocode\Model\Promocode;
use App\Tariff\Model\Tariff;
use App\User\Model\User;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

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
     * @var ProductType
     * @ORM\Column(type="json_document")
     */
    private $type;

    /**
     * @ORM\Column(type="boolean")
     */
    private $reserved;

    public function __construct(ProductId $id, EventId $eventId, ProductType $type, bool $reserved = false)
    {
        $this->id       = $id;
        $this->eventId  = $eventId;
        $this->type     = $type;
        $this->reserved = $reserved;
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
        Promocode $promocode,
        User $user,
        DateTimeImmutable $asOf
    ): Order {
        // TODO replace with check product type ?
        if (!$this->eventId->equals($eventId)) {
            throw new OrderProductMustBeRelatedToEvent();
        }

        return $tariff->makeOrder($orderId, $eventId, $this->id, $promocode, $user, $asOf);
    }
}
