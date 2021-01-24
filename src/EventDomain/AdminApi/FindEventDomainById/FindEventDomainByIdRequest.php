<?php

namespace App\EventDomain\AdminApi\FindEventDomainById;

use App\Infrastructure\Http\RequestResolver\AppRequest;

/**
 * @psalm-immutable
 */
final class FindEventDomainByIdRequest implements AppRequest
{
    public $eventId;

    public function __construct(string $eventId)
    {
        $this->eventId = $eventId;
    }
}
