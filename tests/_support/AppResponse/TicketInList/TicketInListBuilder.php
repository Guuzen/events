<?php

namespace App\Tests\AppResponse\TicketInList;

final class TicketInListBuilder
{
    private $id;

    private $eventId;

    private $ticketType;

    private $number;

    private $reserved;

    private $createdAt;

    private $deliveredAt;

    private function __construct(
        ?string $id,
        ?string $eventId,
        string $ticketType,
        string $number,
        bool $reserved,
        string $createdAt,
        ?string $deliveredAt
    ) {
        $this->id         = $id;
        $this->eventId    = $eventId;
        $this->ticketType = $ticketType;
        $this->number     = $number;
        $this->reserved   = $reserved;
        $this->createdAt  = $createdAt;
        $this->deliveredAt = $deliveredAt;
    }

    public static function any(): self
    {
        return new self(null, null, 'silver_pass', '10002000', false, '@string@.isDateTime()', null);
    }

    public function build(): TicketInList
    {
        return new TicketInList(
            $this->id,
            $this->eventId,
            $this->ticketType,
            $this->number,
            $this->reserved,
            $this->createdAt,
            null
        );
    }

    public function withId(string $id): self
    {
        return new self($id, $this->eventId, $this->ticketType, $this->number, $this->reserved, $this->createdAt, $this->deliveredAt);
    }

    public function withEventId(string $eventId): self
    {
        return new self($this->id, $eventId, $this->ticketType, $this->number, $this->reserved, $this->createdAt, $this->deliveredAt);
    }

    public function withTicketType(string $ticketType): self
    {
        return new self($this->id, $this->eventId, $ticketType, $this->number, $this->reserved, $this->createdAt, $this->deliveredAt);
    }

    public function withNumber(string $number): self
    {
        return new self($this->id, $this->eventId, $this->ticketType, $number, $this->reserved, $this->createdAt, $this->deliveredAt);
    }

    public function withReserved(bool $reserved): self
    {
        return new self($this->id, $this->eventId, $this->ticketType, $this->number, $reserved, $this->createdAt, $this->deliveredAt);
    }

    public function withCreatedAt(string $createdAt): self
    {
        return new self($this->id, $this->eventId, $this->ticketType, $this->number, $this->reserved, $createdAt, $this->deliveredAt);
    }

    public function withDeliveredAt(?string $deliveredAt): self
    {
        return new self($this->id, $this->eventId, $this->ticketType, $this->number, $this->reserved, $this->createdAt, $deliveredAt);
    }
}
