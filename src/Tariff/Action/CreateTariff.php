<?php

declare(strict_types=1);

namespace App\Tariff\Action;

use App\Event\Model\EventId;
use App\Tariff\Model\ProductType;
use App\Tariff\Model\TariffPriceNet;

/**
 * @psalm-immutable
 */
final class CreateTariff
{
    public $eventId;

    public $tariffPriceNet;

    public $productType;

    public function __construct(EventId $eventId, TariffPriceNet $tariffPriceNet, ProductType $productType)
    {
        $this->eventId        = $eventId;
        $this->tariffPriceNet = $tariffPriceNet;
        $this->productType    = $productType;
    }
}
