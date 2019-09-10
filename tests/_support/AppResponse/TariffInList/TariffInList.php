<?php

namespace App\Tests\AppResponse\TariffInList;

final class TariffInList
{
    private $id;

    private $tariffType;

    private $segments;

    private function __construct(string $id, string $tariffType, array $segments)
    {
        $this->id         = $id;
        $this->tariffType = $tariffType;
        $this->segments   = $segments;
    }

    public static function anySilverActiveNowWith(string $tariffId): self
    {
        return new self($tariffId, 'silver_pass', [TariffInListSegment::activeNow200Rub()]);
    }
}
