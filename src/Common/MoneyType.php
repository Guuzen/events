<?php

namespace App\Common;

use Money\Money;

class MoneyType extends JsonDocumentType
{
    public const MONEY = 'app_money';

    protected function className(): string
    {
        return Money::class;
    }

    public function getName(): string
    {
        return self::MONEY;
    }
}
