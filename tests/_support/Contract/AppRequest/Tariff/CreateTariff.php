<?php

namespace App\Tests\Contract\AppRequest\Tariff;

final class CreateTariff
{
    private $eventId;

    private $tariffType;

    private $segments;

    private function __construct(string $eventId, string $tariffType, array $segments)
    {
        $this->eventId    = $eventId;
        $this->tariffType = $tariffType;
        $this->segments   = $segments;
    }

    public static function anySilverActiveNowWith(string $eventId): self
    {
        return new self($eventId, 'silver_pass', [TariffSegment::activeNow200Rub()]);
    }
}
