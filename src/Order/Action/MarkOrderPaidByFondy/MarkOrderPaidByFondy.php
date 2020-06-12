<?php

declare(strict_types=1);

namespace App\Order\Action\MarkOrderPaidByFondy;

use App\Event\Model\EventId;
use App\Order\Model\OrderId;

/**
 * @psalm-immutable
 */
final class MarkOrderPaidByFondy
{
    public $eventId;

    public $orderId;

    public function __construct(EventId $eventId, OrderId $orderId)
    {
        $this->eventId = $eventId;
        $this->orderId = $orderId;
    }
}
