<?php

namespace App\Infrastructure\Http;

use DateTimeImmutable;

/**
 * @psalm-immutable
 */
final class Term
{
    public $start;

    public $end;

    public function __construct(DateTimeImmutable $start, DateTimeImmutable $end)
    {
        $this->start = $start;
        $this->end   = $end;
    }
}
