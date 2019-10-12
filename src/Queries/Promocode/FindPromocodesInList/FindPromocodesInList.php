<?php

namespace App\Queries\Promocode\FindPromocodesInList;

use App\Infrastructure\Http\AppRequest;

/**
 * @psalm-immutable
 */
final class FindPromocodesInList implements AppRequest
{
    public $eventId;

    public function __construct(string $eventId)
    {
        $this->eventId = $eventId;
    }
}
