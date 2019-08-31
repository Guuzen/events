<?php

namespace App\Queries\Event\FindEventById;

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
