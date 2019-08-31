<?php

namespace App\Infrastructure\Http;

// TODO start and end - datetitmeim ?
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

    public function __construct(string $start, string $end)
    {
        $this->start = $start;
        $this->end   = $end;
    }
}
