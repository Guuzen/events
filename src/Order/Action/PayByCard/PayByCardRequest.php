<?php

namespace App\Order\Action\PayByCard;

use App\Event\Model\EventId;
use App\Infrastructure\Http\RequestResolver\AppRequest;
use App\Order\Model\OrderId;

/**
 * @psalm-immutable
 */
final class PayByCardRequest implements AppRequest
{
    private $orderId;

    public function __construct(string $orderId)
    {
        $this->orderId = $orderId;
    }

    public function toPayByCard(EventId $eventId): PayByCard
    {
        return new PayByCard(new OrderId($this->orderId), $eventId);
    }
}
