<?php

namespace App\Infrastructure\Http;

use DateTimeImmutable;

final class Term
{
    /**
     * @readonly
     */
    public $start;

    /**
     * @readonly
     */
    public $end;

    public function __construct(DateTimeImmutable $start, DateTimeImmutable $end)
    {
        $this->start = $start;
        $this->end   = $end;
    }
}
