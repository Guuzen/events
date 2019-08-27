<?php

namespace App\Queries\Tariff;

use App\Infrastructure\Http\AppRequest;

final class FindEventTariffs implements AppRequest
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
