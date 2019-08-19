<?php

namespace App\Tests\AppRequest\Tariff;

final class TariffPriceBuilder
{
    private $amount;

    private $currency;

    private function __construct(string $amount, string $currency)
    {
        $this->amount   = $amount;
        $this->currency = $currency;
    }

    public function withAmount(string $amount): self
    {
        return new self($amount, $this->currency);
    }

    public function withCurrency(string $currency): self
    {
        return new self($this->amount, $currency);
    }

    public static function any(): self
    {
        return new self('200', 'RUB');
    }

    public function build(): TariffPrice
    {
        return new TariffPrice($this->amount, $this->currency);
    }
}
