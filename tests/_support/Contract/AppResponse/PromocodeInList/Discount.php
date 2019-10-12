<?php

namespace App\Tests\Contract\PromocodeInList\AppResponse;

final class Discount
{
    private $amount;

    private $currency;

    private $type;

    private function __construct(string $amount, string $currency, string $type)
    {
        $this->amount   = $amount;
        $this->currency = $currency;
        $this->type     = $type;
    }

    public static function isFixed10Rub(): self
    {
        return new self('10', 'RUB', 'fixed');
    }
}
