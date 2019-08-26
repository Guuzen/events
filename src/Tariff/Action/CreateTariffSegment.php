<?php

namespace App\Tariff\Action;

use App\Infrastructure\Http\Money;
use App\Infrastructure\Http\Term;

final class CreateTariffSegment
{
    /**
     * @readonly
     */
    public $price;

    /**
     * @readonly
     */
    public $term;

    public function __construct(Money $price, Term $term)
    {
        $this->price = $price;
        $this->term  = $term;
    }
}
