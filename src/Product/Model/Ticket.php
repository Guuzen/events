<?php

namespace App\Product\Model;

use App\Event\Model\EventId;
use App\Order\Model\OrderId;
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
     * @ORM\Column(type=TicketId::class)
     */
    private $id;

    /**
     * @ORM\Column(type=EventId::class)
     */
    private $eventId;

    /**
     * @ORM\Column(type=OrderId::class)
     */
    private $orderId;

    /**
     * @ORM\Column(type="string")
     */
    private $number;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    public function __construct(TicketId $id, EventId $eventId, OrderId $orderId, string $number, DateTimeImmutable $createdAt)
    {
        $this->id        = $id;
        $this->eventId   = $eventId;
        $this->number    = $number;
        $this->orderId   = $orderId;
        $this->createdAt = $createdAt;
    }
}
