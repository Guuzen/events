<?php

namespace App\Tests\AppRequest\Tariff;

final class TariffTermBuilder
{
    private $start;

    private $end;

    private function __construct(string $start, string $end)
    {
        $this->start = $start;
        $this->end   = $end;
    }

    public function withStart(string $start): self
    {
        return new self($start, $this->end);
    }

    public function withEnd(string $end): self
    {
        return new self($this->start, $end);
    }

    public static function any(): self
    {
        return new self('2000-01-01 12:00:00', '3000-01-01 12:00:00');
    }

    public function build(): TariffTerm
    {
        return new TariffTerm($this->start, $this->end);
    }
}
