<?php

declare(strict_types=1);

namespace App\Order\Action\ApplyPromocode;

use App\Event\Model\EventId;
use App\Order\Model\OrderId;
use App\Promocode\Model\PromocodeId;

/**
 * @psalm-immutable
 */
final class ApplyPromocode
{
    public $eventId;

    public $promocodeId;

    public $orderId;

    public function __construct(EventId $eventId, PromocodeId $promocodeId, OrderId $orderId)
    {
        $this->eventId     = $eventId;
        $this->promocodeId = $promocodeId;
        $this->orderId     = $orderId;
    }
}
