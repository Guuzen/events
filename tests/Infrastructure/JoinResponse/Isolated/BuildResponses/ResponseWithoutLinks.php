<?php

declare(strict_types=1);

namespace Tests\Infrastructure\JoinResponse\Isolated\BuildResponses;

final class ResponseWithoutLinks
{
    private array $resource;

    public function __construct(array $resource)
    {
        $this->resource = $resource;
    }
}
