<?php
declare(strict_types=1);

namespace App\Promocode\Model\Discount;

use Money\Money;

final class FixedDiscount implements Discount
{
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
