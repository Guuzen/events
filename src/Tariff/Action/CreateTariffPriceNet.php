<?php

namespace App\Tariff\Action;

final class CreateTariffPriceNet
{
    /**
     * @readonly
     *
     * @var CreateTariffSegment[]
     */
    public $segments;

    /**
     * @param CreateTariffSegment[] $segments
     */
    public function __construct(array $segments)
    {
        $this->segments = $segments;
    }
}
