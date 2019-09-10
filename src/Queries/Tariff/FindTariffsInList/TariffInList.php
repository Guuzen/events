<?php

namespace App\Queries\Tariff\FindTariffsInList;

final class TariffInList
{
    /**
     * @readonly
     */
    public $id;

    /**
     * @readonly
     */
    public $tariffType;

    /**
     * @readonly
     */
    public $segments;

    private function __construct(string $id, string $tariffType, array $segments)
    {
        $this->id         = $id;
        $this->tariffType = $tariffType;
        $this->segments   = $segments;
    }
}
