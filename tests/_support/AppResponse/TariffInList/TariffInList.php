<?php

namespace App\Tests\AppResponse\TariffInList;

final class TariffInList implements \JsonSerializable
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

    public function jsonSerialize(): array
    {
        return [
            'id'           => $this->id,
            'product_type' => $this->productType,
            'price'        => $this->price,
            'term_start'   => $this->termStart,
            'term_end'     => $this->termEnd,
        ];
    }
}
