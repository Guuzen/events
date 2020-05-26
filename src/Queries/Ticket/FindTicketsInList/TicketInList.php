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

    public $number;

    public $createdAt;

    public function __construct(
        string $id,
        string $eventId,
        string $number,
        DateTimeImmutable $createdAt
    )
    {
        $this->id        = $id;
        $this->eventId   = $eventId;
        $this->number    = $number;
        $this->createdAt = $createdAt;
    }
}
