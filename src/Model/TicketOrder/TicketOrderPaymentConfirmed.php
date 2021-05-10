<?php

declare(strict_types=1);

namespace App\Model\TicketOrder;

use App\Infrastructure\DomainEvent\Event;

/**
 * @psalm-immutable
 */
final class TicketOrderPaymentConfirmed implements Event
{
    public $eventId;

    public $orderId;

    public $userId;

    public $promocodeId;

    public function __construct(string $eventId, string $orderId, string $userId, ?string $promocodeId)
    {
        $this->eventId     = $eventId;
        $this->orderId     = $orderId;
        $this->userId      = $userId;
        $this->promocodeId = $promocodeId;
    }
}
