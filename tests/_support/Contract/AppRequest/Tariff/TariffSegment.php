<?php

namespace App\Tests\Contract\AppRequest\Tariff;

final class TariffSegment
{
    private $price;

    private $term;

    private function __construct(TariffPrice $price, TariffTerm $term)
    {
        $this->price = $price;
        $this->term  = $term;
    }

    public static function activeNow200Rub(): self
    {
        return new self(TariffPrice::is200Rub(), TariffTerm::activeNow());
    }
}
