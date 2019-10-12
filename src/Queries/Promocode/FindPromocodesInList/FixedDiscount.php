<?php

namespace App\Queries\Promocode\FindPromocodesInList;

/**
 * @psalm-immutable
 */
final class FixedDiscount
{
    public $amount;

    public $currency;

    public $type;

    public function __construct(string $amount, string $currency, string $type)
    {
        $this->amount   = $amount;
        $this->currency = $currency;
        $this->type     = $type;
    }
}
