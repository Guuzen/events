<?php

declare(strict_types=1);

namespace App\Model\TicketOrder;

use App\Infrastructure\DomainEvent\Event;
use App\Model\Event\EventId;
use App\Model\Promocode\PromocodeId;
use App\Model\User\UserId;

/**
 * @psalm-immutable
 */
final class TicketOrderPaymentConfirmed implements Event
{
    public $eventId;

    public $orderId;

    public $userId;

    public $promocodeId;

    public function __construct(EventId $eventId, TicketOrderId $orderId, UserId $userId, ?PromocodeId $promocodeId)
    {
        $this->eventId     = $eventId;
        $this->orderId     = $orderId;
        $this->userId      = $userId;
        $this->promocodeId = $promocodeId;
    }
}
