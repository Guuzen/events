<?php

declare(strict_types=1);

namespace Tests\Infrastructure\JoinResponse;

final class DoubleJoinedOneToManyResponse
{
    private $one;
    private $firstMany;
    private $secondMany;

    public function __construct(array $one, array $firstMany, array $secondMany)
    {
        $this->one        = $one;
        $this->firstMany  = $firstMany;
        $this->secondMany = $secondMany;
    }
}
