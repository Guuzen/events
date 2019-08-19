<?php

namespace App\Tests\AppRequest\Tariff;

final class TariffSegment
{
    private $price;

    private $term;

    public function __construct(TariffPrice $price, TariffTerm $term)
    {
        $this->price = $price;
        $this->term  = $term;
    }
}
