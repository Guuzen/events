<?php

namespace App\Queries\Ticket\FindTicketById;

final class TicketById
{
    /**
     * @readonly
     */
    public $id;

    /**
     * @readonly
     */
    public $eventId;

    /**
     * @readonly
     */
    public $type;

    /**
     * @readonly
     */
    public $number;

    /**
     * @readonly
     */
    public $reserved;

    /**
     * @readonly
     */
    public $createdAt;

    /**
     * @readonly
     */
    public $deliveredAt;

    public function __construct(string $id, string $eventId, string $type, string $number, bool $reserved, string $createdAt, ?string $deliveredAt)
    {
        $this->id          = $id;
        $this->eventId     = $eventId;
        $this->type        = $type;
        $this->number      = $number;
        $this->reserved    = $reserved;
        $this->createdAt   = $createdAt;
        $this->deliveredAt = $deliveredAt;
    }
}
