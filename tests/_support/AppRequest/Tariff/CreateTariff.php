<?php

namespace App\Tests\AppRequest\Tariff;

final class CreateTariff
{
    private $eventId;

    private $productType;

    private $segments;

    private function __construct(string $eventId, string $productType, array $segments)
    {
        $this->eventId     = $eventId;
        $this->productType = $productType;
        $this->segments    = $segments;
    }

    public static function anySilverActiveNowWith(string $eventId): self
    {
        return new self($eventId, 'silver_pass', [TariffSegment::activeNow200Rub()]);
    }
}

