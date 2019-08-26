<?php

namespace App\Infrastructure\Http;

final class Money
{
    /**
     * @readonly
     */
    public $amount;

    /**
     * @readonly
     */
    public $currency;

    public function __construct(string $amount, string $currency)
    {
        $this->amount   = $amount;
        $this->currency = $currency;
    }
}
