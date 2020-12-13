<?php

declare(strict_types=1);

namespace Tests\Infrastructure\JoinResponse\Integrated;

use App\Infrastructure\ArrayComposer\ResourceProvider;

final class StubResourceProvider implements ResourceProvider
{
    private $resources;

    /**
     * @psalm-param array<int, array> $resources
     */
    public function __construct(array $resources)
    {
        $this->resources = $resources;
    }

    public function resources(array $keys): array
    {
        return $this->resources;
    }
}
