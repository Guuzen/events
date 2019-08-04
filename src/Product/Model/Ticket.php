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

    public function __construct(TicketId $id, EventId $eventId, string $number)
    {
        $this->id      = $id;
        $this->eventId = $eventId;
        $this->number  = $number;
    }
}
