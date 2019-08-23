<?php

namespace App\Tests\AppResponse;

final class TariffInList
{
    private $id;

    private $productType;

    private $price;

    private $termStart;

    private $termEnd;

    private function __construct(string $id, string $productType, string $price, string $termStart, string $termEnd)
    {
        $this->id          = $id;
        $this->productType = $productType;
        $this->price       = $price;
        $this->termStart   = $termStart;
        $this->termEnd     = $termEnd;
    }

    public static function anySilverActiveNowWith(string $tariffId): self
    {
        return new self($tariffId, 'silver_pass', '200 RUB', '2000-01-01 12:00:00', '3000-01-01 12:00:00');
    }
}
