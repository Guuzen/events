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
    public $tariffType;

    /**
     * @readonly
     */
    public $segments;

    public function __construct(string $id, string $tariffType, array $segments)
    {
        $this->id         = $id;
        $this->tariffType = $tariffType;
        $this->segments   = $segments;
    }
}
