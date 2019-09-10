<?php

namespace App\Infrastructure\Http;

/**
 * @psalm-immutable
 */
final class Money
{
    public $amount;

    public $currency;

    public function __construct(string $amount, string $currency)
    {
        $this->amount   = $amount;
        $this->currency = $currency;
    }
}
