<?php

namespace App\EventFoo\PaymentByBankCard;

final class BankCard
{
    private $pan;

    private $expYear;

    private $expMonth;

    private $cvc;

    private $holder;

    public function __construct(string $pan, int $expYear, int $expMonth, string $cvc, string $holder)
    {
        $this->pan      = $pan;
        $this->expYear  = $expYear;
        $this->expMonth = $expMonth;
        $this->cvc      = $cvc;
        $this->holder   = $holder;
    }

    public function getPan(): string
    {
        return $this->pan;
    }

    public function getExpYear(): int
    {
        return $this->expYear;
    }

    public function getExpMonth(): int
    {
        return $this->expMonth;
    }

    public function getCvc(): string
    {
        return $this->cvc;
    }

    public function getHolder(): string
    {
        return $this->holder;
    }
}
