<?php

namespace App\Tests\AppResponse;

final class Money
{
    private $amount;

    private $currency;

    private function __construct(string $amount, string $currency)
    {
        $this->amount   = $amount;
        $this->currency = $currency;
    }

    public static function is200Rub(): self
    {
        return new self('200', 'RUB');
    }
}
