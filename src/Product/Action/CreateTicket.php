<?php

namespace App\Product\Action;

use App\Common\AppRequest;

final class CreateTicket implements AppRequest
{
    public $eventId;

    public $tariffId;

    public $number;

    public function __construct(string $eventId, string $tariffId, string $number)
    {
        $this->eventId  = $eventId;
        $this->tariffId = $tariffId;
        $this->number   = $number;
    }
}