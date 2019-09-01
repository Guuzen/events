<?php

namespace App\Queries\Tariff\FindTariffById;

use DateTimeImmutable;

final class TariffByIdTerm
{
    /**
     * @readonly
     */
    public $start;

    /**
     * @readonly
     */
    public $end;

    public function __construct(DateTimeImmutable $start, DateTimeImmutable $end)
    {
        $this->start = $start;
        $this->end   = $end;
    }
}
