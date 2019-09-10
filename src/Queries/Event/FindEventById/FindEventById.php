<?php

namespace App\Queries\Event\FindEventById;

use App\Infrastructure\Http\AppRequest;

/**
 * @psalm-immutable
 */
final class FindEventById implements AppRequest
{
    public $eventId;

    public function __construct(string $eventId)
    {
        $this->eventId = $eventId;
    }
}
