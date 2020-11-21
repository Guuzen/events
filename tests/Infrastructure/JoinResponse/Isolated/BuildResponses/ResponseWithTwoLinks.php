<?php

declare(strict_types=1);

namespace Tests\Infrastructure\JoinResponse\Isolated\BuildResponses;

final class ResponseWithTwoLinks
{
    private array $resource;
    private ResponseWithoutLinks $responseWithoutLinks1;
    private ResponseWithoutLinks $responseWithoutLinks2;

    public function __construct(array $resource, ResponseWithoutLinks $responseWithoutLinks1, ResponseWithoutLinks $responseWithoutLinks2)
    {
        $this->resource              = $resource;
        $this->responseWithoutLinks1 = $responseWithoutLinks1;
        $this->responseWithoutLinks2 = $responseWithoutLinks2;
    }
}
