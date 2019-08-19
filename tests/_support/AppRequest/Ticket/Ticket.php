<?php

namespace App\Tests\AppRequest\Ticket;

final class Ticket implements \JsonSerializable
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

    public function jsonSerialize(): array
    {
        return [
            'event_id'  => $this->eventId,
            'tariff_id' => $this->tariffId,
            'number'    => $this->number,
        ];
    }
}
