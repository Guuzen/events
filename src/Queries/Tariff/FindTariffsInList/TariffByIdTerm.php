<?php

namespace App\Queries\Tariff\FindTariffsInList;

use DateTimeImmutable;

/**
 * @psalm-immutable
 */
final class TariffByIdTerm
{
    public $start;

    public $end;

    public function __construct(DateTimeImmutable $start, DateTimeImmutable $end)
    {
        $this->start = $start;
        $this->end   = $end;
    }
}
