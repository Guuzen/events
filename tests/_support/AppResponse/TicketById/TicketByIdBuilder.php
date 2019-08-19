<?php

namespace App\Tests\AppResponse\TicketById;

final class TicketByIdBuilder
{
    private $id;

    private $eventId;

    private $ticketType;

    private $number;

    private $reserved;

    private function __construct(?string $id, ?string $eventId, string $ticketType, string $number, bool $reserved)
    {
        $this->id         = $id;
        $this->eventId    = $eventId;
        $this->ticketType = $ticketType;
        $this->number     = $number;
        $this->reserved   = $reserved;
    }

    public static function any(): self
    {
        return new self(null, null, 'silver_pass', '10002000', false);
    }

    public function build(): TicketById
    {
        return new TicketById($this->id, $this->eventId, $this->ticketType, $this->number, $this->reserved);
    }

    public function withId(string $id): self
    {
        return new self($id, $this->eventId, $this->ticketType, $this->number, $this->reserved);
    }

    public function withEventId(string $eventId): self
    {
        return new self($this->id, $eventId, $this->ticketType, $this->number, $this->reserved);
    }

    public function withTicketType(string $ticketType): self
    {
        return new self($this->id, $this->eventId, $ticketType, $this->number, $this->reserved);
    }

    public function withNumber(string $number): self
    {
        return new self($this->id, $this->eventId, $this->ticketType, $number, $this->reserved);
    }

    public function withReserved(bool $reserved): self
    {
        return new self($this->id, $this->eventId, $this->ticketType, $this->number, $reserved);
    }
}
