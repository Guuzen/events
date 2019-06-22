<?php
declare(strict_types=1);

namespace App\Promocode\Model\Discount;

use Money\Money;

interface Discount
{
    public function apply(Money $price): Money;
}
