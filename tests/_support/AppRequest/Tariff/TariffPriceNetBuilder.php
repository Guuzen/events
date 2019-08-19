<?php

namespace App\Tests\AppRequest\Tariff;

final class TariffPriceNetBuilder
{
    private $segments;

    private function __construct(array $segments)
    {
        $this->segments = $segments;
    }

    public function withSegment(TariffSegmentBuilder $tariffSegmentBuilder): self
    {
        $segments   = $this->segments;
        $segments[] = $tariffSegmentBuilder->build();

        return new self($segments);
    }

    public static function any(): self
    {
        return new self([TariffSegmentBuilder::tariffSegment()->build()]);
    }

    public function build(): TariffPriceNet
    {
        return new TariffPriceNet($this->segments);
    }
}
