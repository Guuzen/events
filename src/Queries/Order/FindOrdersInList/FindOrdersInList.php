<?php

namespace App\Queries\Order\FindOrdersInList;

use App\Infrastructure\Http\AppRequest;

final class FindOrdersInList implements AppRequest
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
