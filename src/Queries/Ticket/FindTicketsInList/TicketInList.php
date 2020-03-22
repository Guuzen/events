<?php

namespace App\Queries\Ticket\FindTicketsInList;

use DateTimeImmutable;

/**
 * @psalm-immutable
 */
final class TicketInList
{
    public $id;

    public $eventId;

    public $type;

    public $number;

    public $reserved;

    public $createdAt;

    public $deliveredAt;

    public function __construct(
        string $id,
        string $eventId,
        string $type,
        string $number,
        bool $reserved,
        DateTimeImmutable $createdAt,
        ?DateTimeImmutable $deliveredAt
    )
    {
        $this->id          = $id;
        $this->eventId     = $eventId;
        $this->type        = $type;
        $this->number      = $number;
        $this->reserved    = $reserved;
        $this->createdAt   = $createdAt;
        $this->deliveredAt = $deliveredAt;
    }
}
