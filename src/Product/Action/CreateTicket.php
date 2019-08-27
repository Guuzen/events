<?php

namespace App\Product\Action;

use App\Infrastructure\Http\AppRequest;

final class CreateTicket implements AppRequest
{
    /**
     * @readonly
     */
    public $eventId;

    /**
     * @readonly
     */
    public $tariffId;

    /**
     * @readonly
     */
    public $number;

    public function __construct(string $eventId, string $tariffId, string $number)
    {
        $this->eventId  = $eventId;
        $this->tariffId = $tariffId;
        $this->number   = $number;
    }
}
