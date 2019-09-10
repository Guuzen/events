<?php

namespace App\Queries\Tariff\FindTariffsInList;

/**
 * @psalm-immutable
 */
final class TariffInList
{
    public $id;

    public $tariffType;

    public $segments;

    private function __construct(string $id, string $tariffType, array $segments)
    {
        $this->id         = $id;
        $this->tariffType = $tariffType;
        $this->segments   = $segments;
    }
}
