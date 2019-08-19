<?php

namespace App\Tests\AppRequest\Tariff;

final class CreateTariff
{
    private $eventId;

    private $productType;

    private $priceNet;

    public function __construct(string $eventId, string $productType, TariffPriceNet $priceNet)
    {
        $this->eventId     = $eventId;
        $this->productType = $productType;
        $this->priceNet    = $priceNet;
    }
}
