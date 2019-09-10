<?php

namespace App\Queries\Tariff\FindTariffsInList;

use App\Infrastructure\Http\Money;

/**
 * @psalm-immutable
 */
final class TariffByIdSegment
{
    public $price;

    public $term;

    public function __construct(Money $price, TariffByIdTerm $term)
    {
        $this->price = $price;
        $this->term  = $term;
    }
}
