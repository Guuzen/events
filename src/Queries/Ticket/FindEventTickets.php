<?php

namespace App\Queries\Ticket;

use App\Common\AppRequest;

final class FindEventTickets implements AppRequest
{
    public $eventId;

    public function __construct(string $eventId)
    {
        $this->eventId = $eventId;
    }
}
