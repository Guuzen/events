<?php

namespace App\Order\Action;

use App\Event\Model\EventId;
use App\Tariff\Model\TariffId;
use App\User\Model\UserId;

/**
 * @psalm-immutable
 */
final class PlaceOrder
{
    public $tariffId;

    public $eventId;

    public $userId;

    public function __construct(TariffId $tariffId, EventId $eventId, UserId $userId)
    {
        $this->tariffId = $tariffId;
        $this->eventId  = $eventId;
        $this->userId   = $userId;
    }
}
