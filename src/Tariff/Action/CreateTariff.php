<?php

namespace App\Tariff\Action;

use App\Common\AppRequest;

final class CreateTariff implements AppRequest
{
    // TODO detect event id by host
    public $eventId;

    public $productType;

    // TODO embedded object?
    public $priceNet;

    public function __construct(string $eventId, string $productType, CreateTariffPriceNet $priceNet)
    {
        $this->eventId     = $eventId;
        $this->productType = $productType;
        $this->priceNet    = $priceNet;
    }
}
