<?php

namespace App\Tests\AppResponse\TariffInList;

final class TariffInList
{
    private $id;

    private $productType;

    private $segments;

    private function __construct(string $id, string $productType, array $segments)
    {
        $this->id          = $id;
        $this->productType = $productType;
        $this->segments    = $segments;
    }

    public static function anySilverActiveNowWith(string $tariffId): self
    {
        return new self($tariffId, 'silver_pass', [TariffInListSegment::activeNow200Rub()]);
    }
}
