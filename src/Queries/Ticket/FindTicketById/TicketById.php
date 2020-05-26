<?php

namespace App\Queries\Ticket\FindTicketById;

use DateTimeImmutable;

/**
 * @psalm-immutable
 */
final class TicketById
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
