<?php

namespace App\Tests\AppRequest\Tariff;

final class TariffPriceNet
{
    private $segments;

    public function __construct(array $segments)
    {
        $this->segments = $segments;
    }
}
