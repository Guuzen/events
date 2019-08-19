<?php

namespace App\Tests\AppRequest\Ticket;

final class TicketBuilder
{
    private $eventId;

    private $tariffId;

    private $number;

    private function __construct(?string $eventId, ?string $tariffId, string $number)
    {
        $this->eventId  = $eventId;
        $this->tariffId = $tariffId;
        $this->number   = $number;
    }

    public static function any(): self
    {
        return new self(null, null, '10002000');
    }

    public function build(): Ticket
    {
        return new Ticket($this->eventId, $this->tariffId, $this->number);
    }

    public function withEventId(string $eventId): self
    {
        return new self($eventId, $this->tariffId, $this->number);
    }

    public function withTariffId(string $tariffId): self
    {
        return new self($this->eventId, $tariffId, $this->number);
    }

    public function withNumber(string $number): self
    {
        return new self($this->eventId, $this->tariffId, $number);
    }
}
