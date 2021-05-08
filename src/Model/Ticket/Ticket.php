<?php

namespace App\Model\Ticket;

use App\Model\Event\EventId;
use App\Model\TicketOrder\TicketOrderId;
use App\Model\User\UserId;
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
     * @ORM\Column(type=TicketOrderId::class)
     */
    private $orderId;

    /**
     * @ORM\Column(type="string")
     */
    private $number;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type=UserId::class, nullable=false)
     */
    private $userId;

    public function __construct(TicketId $id, EventId $eventId, TicketOrderId $orderId, UserId $userId, string $number, DateTimeImmutable $createdAt)
    {
        $this->id        = $id;
        $this->eventId   = $eventId;
        $this->number    = $number;
        $this->orderId   = $orderId;
        $this->createdAt = $createdAt;
        $this->userId    = $userId;
    }
}
