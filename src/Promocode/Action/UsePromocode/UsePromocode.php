<?php

declare(strict_types=1);

namespace App\Promocode\Action\UsePromocode;

use App\Event\Model\EventId;
use App\Order\Model\OrderId;
use App\Tariff\Model\TariffId;

/**
 * @psalm-immutable
 */
final class UsePromocode
{
    public $orderId;

    public $tariffId;

    public $code;

    public $eventId;

    public function __construct(OrderId $orderId, TariffId $tariffId, EventId $eventId, string $code)
    {
        $this->orderId  = $orderId;
        $this->tariffId = $tariffId;
        $this->code     = $code;
        $this->eventId  = $eventId;
    }
}
