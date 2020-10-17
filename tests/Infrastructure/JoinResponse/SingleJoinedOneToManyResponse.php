<?php

declare(strict_types=1);

namespace Tests\Infrastructure\JoinResponse;

final class SingleJoinedOneToManyResponse
{
    private $one;

    private $many;

    public function __construct(array $one, array $many)
    {
        $this->one  = $one;
        $this->many = $many;
    }
}
