<?php

declare(strict_types=1);

namespace App\Promocode\Action\UsePromocode;

use App\Event\Model\EventId;
use App\Infrastructure\Http\RequestResolver\AppRequest;
use App\Order\Model\OrderId;
use App\Tariff\Model\TariffId;

final class UsePromocodeRequest implements AppRequest
{
    private $orderId;

    private $code;

    private $tariffId;

    public function __construct(string $orderId, string $code, string $tariffId)
    {
        $this->orderId  = $orderId;
        $this->code     = $code;
        $this->tariffId = $tariffId;
    }

    public function toUsePromocode(EventId $eventId): UsePromocode
    {
        return new UsePromocode(
            new OrderId($this->orderId),
            new TariffId($this->tariffId),
            $eventId,
            $this->code
        );
    }
}
