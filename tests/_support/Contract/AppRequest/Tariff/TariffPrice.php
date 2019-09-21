<?php

namespace App\Tests\Contract\AppRequest\Tariff;

final class TariffPrice
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
