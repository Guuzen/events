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
    private $tariffId;

    private $eventId;

    private $userId;

    public function __construct(string $tariffId, string $eventId, string $userId)
    {
        $this->tariffId = $tariffId;
        $this->eventId  = $eventId;
        $this->userId   = $userId;
    }

    public function tariffId(): TariffId
    {
        return new TariffId($this->tariffId);
    }

    public function eventId(): EventId
    {
        return new EventId($this->eventId);
    }

    public function userId(): UserId
    {
        return new UserId($this->userId);
    }
}
