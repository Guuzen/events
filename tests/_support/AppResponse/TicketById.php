<?php

namespace App\Tests\AppResponse;

final class TicketById
{
    private $id;

    private $eventId;

    private $type;

    private $number;

    private $reserved;

    private $createdAt;

    private $deliveredAt;

    private function __construct(string $id, string $eventId, string $type, string $number, bool $reserved, string $createdAt, ?string $deliveredAt)
    {
        $this->id          = $id;
        $this->eventId     = $eventId;
        $this->type        = $type;
        $this->number      = $number;
        $this->reserved    = $reserved;
        $this->createdAt   = $createdAt;
        $this->deliveredAt = $deliveredAt;
    }

    public static function anySilverNotReservedNotDeliveredWith(string $ticketId, string $eventId): self
    {
        return new self($ticketId, $eventId, 'silver_pass', '10002000', false, '@string@.isDateTime()', null);
    }

    public static function anySilverReservedNotDeliveredWith(string $ticketId, string $eventId): self
    {
        return new self($ticketId, $eventId, 'silver_pass', '10002000', true, '@string@.isDateTime()', null);
    }

    public static function anySilverReservedDeliveredWith(string $ticketId, string $eventId): self
    {
        return new self($ticketId, $eventId, 'silver_pass', '10002000', true, '@string@.isDateTime()', '@string@.isDateTime()');
    }
}
