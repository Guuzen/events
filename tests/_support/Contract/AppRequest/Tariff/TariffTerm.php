<?php

namespace App\Tests\Contract\AppRequest\Tariff;

final class TariffTerm
{
    private $start;

    private $end;

    private function __construct(string $start, string $end)
    {
        $this->start = $start;
        $this->end   = $end;
    }

    public static function activeNow(): self
    {
        return new self('2000-01-01 12:00:00', '3000-01-01 12:00:00');
    }
}
