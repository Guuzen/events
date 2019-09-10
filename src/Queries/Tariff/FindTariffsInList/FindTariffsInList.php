<?php

namespace App\Queries\Tariff\FindTariffsInList;

use App\Infrastructure\Http\AppRequest;

/**
 * @psalm-immutable
 */
final class FindTariffsInList implements AppRequest
{
    public $eventId;

    public function __construct(string $eventId)
    {
        $this->eventId = $eventId;
    }
}
