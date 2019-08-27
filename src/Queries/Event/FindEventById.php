<?php

namespace App\Queries\Event;

use App\Infrastructure\Http\AppRequest;

final class FindEventById implements AppRequest
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
