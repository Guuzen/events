<?php

declare(strict_types=1);

namespace App\Promocode\Query\ApplyDiscountHandler;

use App\Promocode\Model\PromocodeId;
use Money\Money;

/**
 * @psalm-immutable
 */
final class ApplyDiscount
{
    public $promocodeId;

    public $money;

    public function __construct(PromocodeId $promocodeId, Money $money)
    {
        $this->promocodeId = $promocodeId;
        $this->money       = $money;
    }
}
