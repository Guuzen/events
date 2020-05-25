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

    public $productType;

    // TODO this objects do not use constructors now
    private function __construct(string $id, string $tariffType, array $segments, string $productType)
    {
        $this->id          = $id;
        $this->tariffType  = $tariffType;
        $this->segments    = $segments;
        $this->productType = $productType;
    }
}
