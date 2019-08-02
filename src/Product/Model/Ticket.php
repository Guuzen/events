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
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity
 * @ORM\Table(uniqueConstraints={
 *     @UniqueConstraint(name="unique_idx", columns={"event_id", "number"})
 * })
 */
final class Ticket
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="app_ticket_id")
     */
    private $id;

    /**
     * @ORM\Column(type="app_event_id")
     */
    private $eventId;

    /**
     * @ORM\Column(type="string")
     */
    private $number;

    /**
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * @ORM\Column(type="boolean")
     */
    private $reserved;

    public function __construct(TicketId $id, EventId $eventId, string $number, string $status, bool $reserved = true)
    {
        $this->id       = $id;
        $this->number   = $number;
        $this->status   = $status;
        $this->reserved = $reserved;
        $this->eventId  = $eventId;
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
        if (!$this->eventId->equals($eventId)) {
            throw new OrderProductMustBeRelatedToEvent();
        }
        $productId = ProductId::fromString($this->id);

        return $tariff->makeOrder($orderId, $eventId, $productId, $promocode, $user, $asOf);
    }
}
