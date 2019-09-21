<?php

namespace App\Tests\Contract\AppRequest\Ticket;

final class CreateTicket
{
    private $eventId;

    private $tariffId;

    private $number;

    private function __construct(string $eventId, string $tariffId, string $number)
    {
        $this->eventId  = $eventId;
        $this->tariffId = $tariffId;
        $this->number   = $number;
    }

    public static function anyWith(string $eventId, string $tariffId): self
    {
        return new self($eventId, $tariffId, '10002000');
    }
}
