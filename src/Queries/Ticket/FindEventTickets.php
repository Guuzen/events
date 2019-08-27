<?php

namespace App\Queries\Ticket;

use App\Infrastructure\Http\AppRequest;

final class FindEventTickets implements AppRequest
{
    /**
     * @readonly
     */
    public $eventId;

    public function __construct(string $eventId)
    {
        $this->eventId = $eventId;
    }
}
