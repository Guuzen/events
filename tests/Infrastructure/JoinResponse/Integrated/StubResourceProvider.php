<?php

declare(strict_types=1);

namespace Tests\Infrastructure\JoinResponse\Integrated;

use App\Infrastructure\ResponseComposer\ResourceProvider;

/**
 * @template Resource as object|array
 */
final class StubResourceProvider implements ResourceProvider
{
    /**
     * @psalm-var Resource[]
     */
    private array $resources;

    /**
     * @psalm-param Resource[] $resources
     */
    public function __construct(array $resources)
    {
        $this->resources = $resources;
    }

    /**
     * @psalm-return Resource[]
     */
    public function resources(array $keys): array
    {
        return $this->resources;
    }
}
