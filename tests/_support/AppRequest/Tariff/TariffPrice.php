<?php

namespace App\Tests\AppRequest\Tariff;

final class TariffPrice implements \JsonSerializable
{
    private $amount;

    private $currency;

    public function __construct(string $amount, string $currency)
    {
        $this->amount   = $amount;
        $this->currency = $currency;
    }

    public function jsonSerialize(): array
    {
        return [
            'amount'   => $this->amount,
            'currency' => $this->currency,
        ];
    }
}
