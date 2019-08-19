<?php

namespace App\Tests\AppResponse\TicketInTicketList;

final class TicketInTicketList implements \JsonSerializable
{
    private $id;

    private $eventId;

    private $ticketType;

    private $number;

    private $reserved;

    public function __construct(string $id, string $eventId, string $ticketType, string $number, bool $reserved)
    {
        $this->id         = $id;
        $this->eventId    = $eventId;
        $this->ticketType = $ticketType;
        $this->number     = $number;
        $this->reserved   = $reserved;
    }

    public function jsonSerialize(): array
    {
        return [
            'id'       => $this->id,
            'event_id' => $this->eventId,
            'type'     => $this->ticketType,
            'number'   => $this->number,
            'reserved' => $this->reserved,
        ];
    }
}
