<?php

namespace App\Queries\Tariff;

use App\Common\AppRequest;

final class FindEventTariffs implements AppRequest
{
    public $eventId;

    public function __construct(string $eventId)
    {
        $this->eventId = $eventId;
    }
}
