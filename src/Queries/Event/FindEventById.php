<?php

namespace App\Queries\Event;

use App\Common\AppRequest;

final class FindEventById implements AppRequest
{
    public $eventId;

    public function __construct(string $eventId)
    {
        $this->eventId = $eventId;
    }
}
