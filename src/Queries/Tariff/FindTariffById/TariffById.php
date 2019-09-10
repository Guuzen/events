<?php

namespace App\Queries\Tariff\FindTariffById;

/**
 * @psalm-immutable
 */
final class TariffById
{
    public $id;

    public $tariffType;

    public $segments;

    public function __construct(string $id, string $tariffType, array $segments)
    {
        $this->id         = $id;
        $this->tariffType = $tariffType;
        $this->segments   = $segments;
    }
}
