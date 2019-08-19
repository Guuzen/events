<?php

namespace App\Tests\AppRequest\Tariff;

final class TariffSegment implements \JsonSerializable
{
    private $price;

    private $term;

    public function __construct(TariffPrice $price, TariffTerm $term)
    {
        $this->price = $price;
        $this->term  = $term;
    }

    public function jsonSerialize(): array
    {
        return [
            'price' => $this->price->jsonSerialize(),
            'term'  => $this->term->jsonSerialize(),
        ];
    }
}
