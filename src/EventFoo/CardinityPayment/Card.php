<?php

namespace App\EventFoo\CardinityPayment;

final class Card
{
    private $pan;

    private $expYear;

    private $expMonth;

    private $cvc;

    private $holder;

    public function __construct(string $pan, int $expYear, int $expMonth, string $cvc, string $holder)
    {
        $this->pan = $pan;

        $this->assertExpYear($expYear);
        $this->expYear = $expYear;

        $this->assertExpMonth($expMonth);
        $this->expMonth = $expMonth;

        $this->assertCvc($cvc);
        $this->cvc = $cvc;

        $this->assertHolder($holder);
        $this->holder = $holder;
    }

    private function assertExpYear(int $expYear): void
    {
        if ($expYear < 1000 || $expYear > 9999) {
            throw new \Exception('Expiration year must be between 1000 and 9999');
        }
    }

    private function assertExpMonth(int $expMonth): void
    {
        if ($expMonth < 1 || $expMonth > 12) {
            throw new \Exception('Expiration year must be between 1 and 12');
        }
    }

    private function assertCvc(string $cvc): void
    {
        if (3 !== mb_strlen($cvc)) {
            throw new \Exception('CVC code length must be equals 3');
        }
    }

    private function assertHolder(string $holder): void
    {
        if (mb_strlen($holder) > 32) {
            throw new \Exception('Card holders name max lenfth must be max 32 characters');
        }
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
