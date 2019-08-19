<?php

namespace App\Tests\AppRequest\Tariff;

final class TariffPriceNet implements \JsonSerializable
{
    /** @var TariffSegment[] */
    private $segments;

    public function __construct(array $segments)
    {
        $this->segments = $segments;
    }

    public function jsonSerialize(): array
    {
        $segments = [];

        foreach ($this->segments as $segment) {
            $segments[] = $segment->jsonSerialize();
        }

        return $segments;
    }
}
