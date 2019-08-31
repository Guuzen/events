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
    public $price;

    /**
     * @readonly
     */
    public $termStart;

    /**
     * @readonly
     */
    public $termEnd;

    private function __construct(string $id, string $productType, string $price, string $termStart, string $termEnd)
    {
        $this->id          = $id;
        $this->productType = $productType;
        $this->price       = $price;
        $this->termStart   = $termStart;
        $this->termEnd     = $termEnd;
    }
}
