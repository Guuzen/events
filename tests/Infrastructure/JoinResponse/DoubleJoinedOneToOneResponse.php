<?php

declare(strict_types=1);

namespace Tests\Infrastructure\JoinResponse;

final class DoubleJoinedOneToOneResponse
{
    private $firstOne;
    private $secondOne;
    private $thirdOne;

    public function __construct(array $firstOne, array $secondOne, array $thirdOne)
    {
        $this->firstOne  = $firstOne;
        $this->secondOne = $secondOne;
        $this->thirdOne  = $thirdOne;
    }
}
