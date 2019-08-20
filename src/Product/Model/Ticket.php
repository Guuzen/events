<?php

namespace App\Product\Model;

use App\Event\Model\EventId;
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
     * @ORM\Column(type="boolean")
     */
    private $delivered;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $deliveredAt;

    // TODO ticket type ?
    public function __construct(TicketId $id, EventId $eventId, string $number, bool $delivered = false)
    {
        $this->id        = $id;
        $this->eventId   = $eventId;
        $this->number    = $number;
        $this->delivered = $delivered;
    }
}
