<?php

namespace App\Order\Action;

/**
 * @psalm-immutable
 */
final class PlaceOrder
{
    public $tariffId;

    public $eventId;

    public $userId;

    public function __construct(string $tariffId, string $eventId, string $userId) // TODO command in model terms ?
    {
        $this->tariffId = $tariffId;
        $this->eventId  = $eventId;
        $this->userId   = $userId;
    }
}
