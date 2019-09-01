<?php

namespace App\Tests\AppResponse\TariffInList;

use App\Tests\AppResponse\Money;

final class TariffInListSegment
{
    private $price;

    private $term;

    public function __construct(Money $price, TariffInListTerm $term)
    {
        $this->price = $price;
        $this->term  = $term;
    }

    public static function activeNow200Rub(): self
    {
        return new self(Money::is200Rub(), TariffInListTerm::activeNow());
    }
}
