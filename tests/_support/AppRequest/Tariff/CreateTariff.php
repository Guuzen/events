<?php

namespace App\Tests\AppRequest\Tariff;

final class CreateTariff implements \JsonSerializable
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

    public function jsonSerialize(): array
    {
        return [
            'event_id'     => $this->eventId,
            'product_type' => $this->productType,
            'price_net'    => $this->priceNet->jsonSerialize(),
        ];
    }
}
