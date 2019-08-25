<?php

namespace App\Queries\Order;

use App\Common\AppRequest;

final class FindEventOrders implements AppRequest
{
    public $eventId;

    public function __construct(string $eventId)
    {
        $this->eventId = $eventId;
    }
}
