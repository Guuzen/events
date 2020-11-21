<?php

declare(strict_types=1);

namespace Tests\Infrastructure\JoinResponse\Isolated\BuildResponses;

final class ResponseWithOneLink
{
    private array $resource;
    private ResponseWithoutLinks $responseWithoutLinks;

    public function __construct(array $resource, ResponseWithoutLinks $responseWithoutLinks)
    {
        $this->resource             = $resource;
        $this->responseWithoutLinks = $responseWithoutLinks;
    }
}
