<?php

namespace App\Tests\AppRequest\Tariff;

final class TariffSegmentBuilder
{
    private $price;

    private $term;

    private function __construct(TariffPrice $price, TariffTerm $term)
    {
        $this->price = $price;
        $this->term  = $term;
    }

    public function withPrice(TariffPriceBuilder $tariffPriceBuilder): self
    {
        return new self($tariffPriceBuilder->build(), $this->term);
    }

    public function withTerm(TariffTermBuilder $tariffTermBuilder): self
    {
        return new self($this->price, $tariffTermBuilder->build());
    }

    public static function tariffSegment(): self
    {
        return new self(TariffPriceBuilder::any()->build(), TariffTermBuilder::any()->build());
    }

    public function build(): TariffSegment
    {
        return new TariffSegment($this->price, $this->term);
    }
}
