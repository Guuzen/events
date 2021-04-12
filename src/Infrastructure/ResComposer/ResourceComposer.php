<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer;

use App\Infrastructure\ResComposer\Link\Link;

final class ResourceComposer
{
    private $denormalizer;

    private $promises;

    /**
     * @var array<int, ResourceResolver>
     */
    private $resolvers = [];

    /**
     * @var array<class-string<ResourceDataLoader>, ResourceDataLoader>
     */
    private $loaders = [];

    public function __construct(ResourceDenormalizer $denormalizer, PromiseCollection $promises)
    {
        $this->denormalizer = $denormalizer;
        $this->promises     = $promises;
    }

    public function registerLoader(ResourceDataLoader $loader): void
    {
        if (isset($this->loaders[\get_class($loader)]) === true) {
            throw new \RuntimeException(
                \sprintf('Loader with type %s already exists in composer', \get_class($loader))
            );
        }

        $this->loaders[\get_class($loader)] = $loader;
    }

    public function registerResolver(ResourceResolver $resolver): void
    {
        $this->resolvers[] = $resolver;
    }

    /**
     * @template T of Resource
     *
     * @param array<int, array> $resources
     * @param class-string<T>   $resourceType
     *
     * @return array<int, T>
     */
    public function compose(array $resources, string $resourceType): array
    {
        $result = $this->denormalizer->denormalize($resources, $resourceType, $this->resolvers);

        $this->processResources();

        return $result;
    }

    /**
     * @template T of Resource
     *
     * @param class-string<T> $resourceType
     *
     * @return T
     */
    public function composeOne(array $resource, string $resourceType)
    {
        return $this->compose([$resource], $resourceType)[0];
    }

    private function processResources(): void
    {
        $promiseGroups = $this->promises->release($this->denormalizer);
        if (0 === \count($promiseGroups)) {
            return;
        }

        foreach ($promiseGroups as $resolverId => $promiseGroup) {
            if (isset($this->resolvers[$resolverId]) === false) {
                throw new \RuntimeException(
                    \sprintf('There is no resolver with id %s', $resolverId)
                );
            }
            $resolver = $this->resolvers[$resolverId];
            $loader   = $this->loaders[$resolver->loader];
            $promiseGroup->resolve($loader, $resolver->link, $resolver->relatedResource, $this->resolvers);
        }

        $this->processResources();
    }
}
