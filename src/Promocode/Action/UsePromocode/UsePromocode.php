<?php

declare(strict_types=1);

namespace App\Promocode\Action\UsePromocode;

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

    public function __construct(OrderId $orderId, TariffId $tariffId, string $code)
    {
        $this->orderId  = $orderId;
        $this->tariffId = $tariffId;
        $this->code     = $code;
    }
}
