<?php

declare(strict_types=1);

namespace App\Promocode\ViewModel\Discount;

use Money\Money;

/**
 * @psalm-immutable
 */
final class FixedDiscount
{
    /**
     * @var Money
     */
    private $amount;

    public function __construct(Money $amount)
    {
        $this->amount = $amount;
    }

    public function apply(Money $price): Money
    {
        return $price->subtract($this->amount);
    }
}
