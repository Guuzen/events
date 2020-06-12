<?php

namespace App\Order\Action\MarkOrderPaidByFondy;

use App\Event\Model\EventId;
use App\Infrastructure\Http\RequestResolver\AppRequest;
use App\Order\Model\OrderId;

/**
 * @psalm-immutable
 */
final class MarkOrderPaidByFondyRequest implements AppRequest
{
    private $orderId;

    public function __construct(string $orderId)
    {
        $this->orderId = $orderId;
    }

    public function toMarkOrderPaidByFondy(EventId $eventId): MarkOrderPaidByFondy
    {
        return new MarkOrderPaidByFondy($eventId, new OrderId($this->orderId));
    }
}
