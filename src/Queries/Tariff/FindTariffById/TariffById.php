<?php

namespace App\Queries\Tariff\FindTariffById;

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
