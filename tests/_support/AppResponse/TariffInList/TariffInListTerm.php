<?php

namespace App\Tests\AppResponse\TariffInList;

use DateTimeImmutable;

final class TariffInListTerm
{
    private $start;

    private $end;

    private function __construct(DateTimeImmutable $start, DateTimeImmutable $end)
    {
        $this->start = $start;
        $this->end   = $end;
    }

    public static function activeNow(): self
    {
        return new self(new DateTimeImmutable('2000-01-01 12:00:00'), new DateTimeImmutable('3000-01-01 12:00:00'));
    }
}
