<?php

namespace App\Tests\AppRequest\Tariff;

final class TariffTerm implements \JsonSerializable
{
    private $start;

    private $end;

    public function __construct(string $start, string $end)
    {
        $this->start = $start;
        $this->end   = $end;
    }

    public function jsonSerialize(): array
    {
        return [
            'start' => $this->start,
            'end'   => $this->end,
        ];
    }
}
