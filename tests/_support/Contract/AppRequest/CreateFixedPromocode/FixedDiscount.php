<?php

namespace App\Tests\Contract\AppRequest\CreateFixedPromocode;

final class FixedDiscount
{
    private $amount;

    private $currency;

    private function __construct(string $amount, string $currency)
    {
        $this->amount   = $amount;
        $this->currency = $currency;
    }

    public static function is10Rub(): self
    {
        return new self('10', 'RUB');
    }
}
