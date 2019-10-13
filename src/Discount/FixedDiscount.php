<?php

namespace App\Discount;

use Money\Money;

final class FixedDiscount
{
    private $amount;

    public function __construct(Money $amount)
    {
        $this->amount = $amount;
    }

    public function applyTo(Money $money): Money
    {
        return $money->subtract($this->amount);
    }
}
