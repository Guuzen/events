<?php

namespace App\Queries\Order;

use App\Infrastructure\Http\AppRequest;

final class FindEventOrders implements AppRequest
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
