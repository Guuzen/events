<?php

namespace App\Queries\Tariff\FindTariffById;

/** TODO wrong response - tariff have many prices and terms for them */
final class TariffById
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

    public function __construct(string $id, string $productType, array $segments)
    {
        $this->id          = $id;
        $this->productType = $productType;
        $this->segments    = $segments;
    }
}
