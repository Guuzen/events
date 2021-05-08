<?php

namespace App\Model\Promocode\Discount;

use Money\Money;

/**
 * @psalm-immutable
 */
final class FixedDiscount implements Discount
{
    /**
     * @var Money
     */
    private $amount;

    public function __construct(Money $amount)
    {
        $this->amount = $amount;
    }

    public function applyTo(Money $price): Money
    {
        return $price->subtract($this->amount);
    }
}
