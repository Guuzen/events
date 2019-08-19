<?php

namespace App\Tests\AppRequest\Tariff;

final class TariffPrice
{
    private $amount;

    private $currency;

    public function __construct(string $amount, string $currency)
    {
        $this->amount   = $amount;
        $this->currency = $currency;
    }
}
