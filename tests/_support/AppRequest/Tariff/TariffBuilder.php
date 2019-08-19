<?php

namespace App\Tests\AppRequest\Tariff;

final class TariffBuilder
{
    private $eventId;

    private $productType;

    private $priceNet;

    private function __construct(?string $eventId, string $productType, TariffPriceNet $priceNet)
    {
        $this->eventId     = $eventId;
        $this->productType = $productType;
        $this->priceNet    = $priceNet;
    }

    public function withEventId(string $eventId): self
    {
        return new self($eventId, $this->productType, $this->priceNet);
    }

    public function withProductType(string $productType): self
    {
        return new self($this->eventId, $productType, $this->priceNet);
    }

    public function withPriceNet(TariffPriceNetBuilder $tariffPriceNetBuilder): self
    {
        return new self($this->eventId, $this->productType, $tariffPriceNetBuilder->build());
    }

    public static function any(): self
    {
        return new self(null, 'silver_pass', TariffPriceNetBuilder::any()->build());
    }

    public function build(): Tariff
    {
        return new Tariff($this->eventId, $this->productType, $this->priceNet);
    }
}
