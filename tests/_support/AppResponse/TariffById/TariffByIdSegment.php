<?php

namespace App\Tests\AppResponse\TariffById;

use App\Tests\AppResponse\Money;

final class TariffByIdSegment
{
    private $price;

    private $term;

    private function __construct(Money $price, TariffByIdTerm $term)
    {
        $this->price = $price;
        $this->term  = $term;
    }

    public static function activeNow200Rub(): self
    {
        return new self(Money::is200Rub(), TariffByIdTerm::activeNow());
    }
}
