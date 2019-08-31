<?php

namespace App\Queries\Tariff\FindTariffsInList;

use App\Infrastructure\Http\AppRequest;

final class FindTariffsInList implements AppRequest
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
