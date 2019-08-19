<?php

namespace App\Tests\AppResponse\TariffById;

final class TariffById
{
    private $id;

    private $productType;

    private $price;

    private $termStart;

    private $termEnd;

    public function __construct(string $id, string $productType, string $price, string $termStart, string $termEnd)
    {
        $this->id          = $id;
        $this->productType = $productType;
        $this->price       = $price;
        $this->termStart   = $termStart;
        $this->termEnd     = $termEnd;
    }
}
