<?php

namespace App\Queries\Tariff\FindTariffsInList;

use App\Infrastructure\Http\Money;

final class TariffByIdSegment
{
    /**
     * @readonly
     */
    public $price;

    /**
     * @readonly
     */
    public $term;

    public function __construct(Money $price, TariffByIdTerm $term)
    {
        $this->price = $price;
        $this->term  = $term;
    }
}
