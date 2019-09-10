<?php

namespace App\Queries\Order\FindOrdersInList;

use App\Infrastructure\Http\AppRequest;

/**
 * @psalm-immutable
 */
final class FindOrdersInList implements AppRequest
{
    public $eventId;

    public function __construct(string $eventId)
    {
        $this->eventId = $eventId;
    }
}
