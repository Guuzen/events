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
    public $productType;

    /**
     * @readonly
     */
    public $segments;

    private function __construct(string $id, string $productType, array $segments)
    {
        $this->id          = $id;
        $this->productType = $productType;
        $this->segments    = $segments;
    }
}
