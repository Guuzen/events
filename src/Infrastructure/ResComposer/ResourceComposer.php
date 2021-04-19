<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer;

use App\Infrastructure\ResComposer\Link\Link;
use App\Infrastructure\ResComposer\PromiseCollector\PromiseCollector;

/**
 * @psalm-type Config=array{0: string, 1: Link, 2: string, 3: ResourceDataLoader, 4: PromiseCollector}
 */
final class ResourceComposer
{
    /**
     * @var PromiseCollection
     */
    private $promises;

    /**
     * @var array<int, Config>
     */
    private $configs = [];

    /**
     * @var array<class-string<ResourceDataLoader>, ResourceDataLoader>
     */
    private $loaders = [];

    public function __construct()
    {
        $this->promises = new PromiseCollection();
    }

    public function registerLoader(ResourceDataLoader $loader): void
    {
        if (isset($this->loaders[\get_class($loader)]) === true) {
            throw new \RuntimeException(
                \sprintf('Loader with type %s already exists in resource composer', \get_class($loader))
            );
        }

        $this->loaders[\get_class($loader)] = $loader;
    }

    /**
     * @param class-string<ResourceDataLoader> $loaderType
     */
    public function registerConfig(
        string $resourceType,
        Link $link,
        string $relatedResourceType,
        string $loaderType,
        PromiseCollector $collector
    ): void
    {
        if (isset($this->loaders[$loaderType]) === false) {
            throw new \RuntimeException(
                \sprintf('Loader with type %s is not exists in resource composer. Register loader first.', $loaderType)
            );
        }
        $loader = $this->loaders[$loaderType];

        $this->configs[] = [$resourceType, $link, $relatedResourceType, $loader, $collector];
    }

    /**
     * @param array<array-key, array> $resources
     *
     * @return array<array-key, array>
     */
    public function compose(array $resources, string $resourceType): array
    {
        $collectors = $this->resourceCollectors($resourceType);
        $result     = self::denormalize($resources);
        $this->promises->remember($result, $collectors);

        $this->processResources();

        return self::normalize($result);
    }

    public function composeOne(array $resource, string $resourceType): array
    {
        return $this->compose([$resource], $resourceType)[0];
    }

    private function processResources(): void
    {
        $promiseGroups = $this->promises->release();
        if (0 === \count($promiseGroups)) {
            return;
        }

        foreach ($promiseGroups as $configId => $promiseGroup) {
            $this->resolvePromises($promiseGroup, $configId);
        }

        $this->processResources();
    }

    /**
     * @param array<int, Promise> $promises
     */
    private function resolvePromises(array $promises, int $configId): void
    {
        /**
         * @var Link               $link
         * @var ResourceDataLoader $loader
         */
        [1 => $link, 2 => $relatedResourceType, 3 => $loader] = $this->configs[$configId];

        $ids = [];
        foreach ($promises as $promise) {
            $id = $promise->id();
            if ($id === null) {
                continue;
            }
            $ids[] = $id;
        }

        /** @psalm-suppress MixedArgumentTypeCoercion TODO update psalm */
        $loadedResources = $loader->load(\array_unique($ids));

        $collectors            = $this->resourceCollectors($relatedResourceType);
        $denormalizedResources = self::denormalize($loadedResources);
        $this->promises->remember($denormalizedResources, $collectors);

        $groupedResources = $link->group($denormalizedResources);

        foreach ($promises as $promise) {
            $promiseId = $promise->id();
            if ($promiseId === null) {
                $promise->resolve($link->defaultEmptyValue());
            } else {
                $promise->resolve($groupedResources[$promiseId] ?? $link->defaultEmptyValue());
            }
        }
    }

    /**
     * @return array<int, PromiseCollector>
     */
    private function resourceCollectors(string $resourceType): array
    {
        $collectors = [];
        foreach ($this->configs as $configId => $config) {
            if ($config[0] === $resourceType) {
                $collectors[$configId] = $config[4];
            }
        }

        return $collectors;
    }

    /**
     * @param array<array-key, array> $resources
     *
     * @return array<array-key, \ArrayObject>
     */
    private static function denormalize(array $resources): array
    {
        $arrayObjects = [];
        foreach ($resources as $key => $resource) {
            $arrayObjects[] = new \ArrayObject($resource);
        }

        return $arrayObjects;
    }

    /**
     * @psalm-suppress MixedReturnTypeCoercion
     *
     * @return array<array-key, array>
     */
    private static function normalize(iterable $iterable): array
    {
        $result = [];
        /**
         * @psalm-var mixed $item
         * @var array-key   $key
         */
        foreach ($iterable as $key => $item) {
            if (\is_iterable($item) === true) {
                $result[$key] = self::normalize($item);
            } else {
                /** @psalm-suppress MixedAssignment */
                $result[$key] = $item;
            }
        }

        return $result;
    }
}
