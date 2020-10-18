<?php

declare(strict_types=1);

namespace Tests\Infrastructure\JoinResponse;

final class SingleJoinedOneToPossiblyNullOneResponse
{
    private $firstOne;
    private $secondOne;

    public function __construct(array $firstOne, ?array $secondOne)
    {
        $this->firstOne  = $firstOne;
        $this->secondOne = $secondOne;
    }
}
