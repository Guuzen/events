<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer;

use App\Infrastructure\ResComposer\Link\Link;

final class PromiseGroup
{
    private $promises;
    private $denormalizer;

    /**
     * @param array<int, Promise> $promises
     */
    public function __construct(array $promises, ResourceDenormalizer $denormalizer)
    {
        $this->promises     = $promises;
        $this->denormalizer = $denormalizer;
    }

    /**
     * @param class-string $resourceType
     * @param array<int, ResourceResolver> $resolvers
     */
    public function resolve(ResourceDataLoader $loader, Link $link, string $resourceType, array $resolvers): void
    {
        $ids = [];
        foreach ($this->promises as $promise) {
            $id = $promise->id();
            if ($id === null) {
                continue;
            }
            $ids[] = $id;
        }

        $loadedResources = $loader->load(\array_unique($ids));

        $denormalizedResources = $this->denormalizer->denormalize($loadedResources, $resourceType, $resolvers);

        $groupedResources = $link->group($denormalizedResources);

        foreach ($this->promises as $promise) {
            $promiseId = $promise->id();
            if ($promiseId === null) {
                $promise->resolve($link->defaultEmptyValue());
            } else {
                $promise->resolve($groupedResources[$promiseId] ?? $link->defaultEmptyValue());
            }
        }
    }
}
