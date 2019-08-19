<?php

namespace App\Tests\AppRequest\Tariff;

final class TariffTerm
{
    private $start;

    private $end;

    public function __construct(string $start, string $end)
    {
        $this->start = $start;
        $this->end   = $end;
    }
}
