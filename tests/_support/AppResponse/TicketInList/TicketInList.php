<?php

namespace App\Tests\AppResponse\TicketInList;

final class TicketInList
{
    private $id;

    private $eventId;

    private $type;

    private $number;

    private $reserved;

    private $createdAt;

    private $deliveredAt;

    public function __construct(
        string $id,
        string $eventId,
        string $type,
        string $number,
        bool $reserved,
        string $createdAt,
        ?string $deliveredAt
    ) {
        $this->id          = $id;
        $this->eventId     = $eventId;
        $this->type        = $type;
        $this->number      = $number;
        $this->reserved    = $reserved;
        $this->createdAt   = $createdAt;
        $this->deliveredAt = $deliveredAt;
    }
}
