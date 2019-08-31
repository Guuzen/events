<?php

namespace App\Queries\Ticket\FindTicketsInList;

use App\Infrastructure\Http\AppRequest;

final class FindTicketsInList implements AppRequest
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
