<?php

namespace App\Tests\AppRequest\Tariff;

final class TariffPriceNet
{
    private $segments;

    private function __construct(array $segments)
    {
        $this->segments = $segments;
    }

    public static function withOne200RubSegmentActiveNow(): self
    {
        return new self([TariffSegment::activeNow200Rub()]);
    }
}
