<?php

namespace App\Queries\Ticket\FindTicketsInList;

use App\Infrastructure\Http\AppRequest;

/**
 * @psalm-immutable
 */
final class FindTicketsInList implements AppRequest
{
    public $eventId;

    public function __construct(string $eventId)
    {
        $this->eventId = $eventId;
    }
}
