<?php

namespace App\Tests\AppRequest\Tariff;

final class CreateTariff
{
    private $eventId;

    private $productType;

    private $priceNet;

    private function __construct(string $eventId, string $productType, TariffPriceNet $priceNet)
    {
        $this->eventId     = $eventId;
        $this->productType = $productType;
        $this->priceNet    = $priceNet;
    }

    public static function anySilverActiveNowWith(string $eventId): self
    {
        return new self($eventId, 'silver_pass', TariffPriceNet::withOne200RubSegmentActiveNow());
    }
}

