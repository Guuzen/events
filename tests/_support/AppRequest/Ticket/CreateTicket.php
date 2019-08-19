<?php

namespace App\Tests\AppRequest\Ticket;

final class CreateTicket
{
    private $eventId;

    private $tariffId;

    private $number;

    public function __construct(string $eventId, string $tariffId, string $number)
    {
        $this->eventId  = $eventId;
        $this->tariffId = $tariffId;
        $this->number   = $number;
    }
}
