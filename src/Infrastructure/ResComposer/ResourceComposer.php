<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer;

final class ResourceComposer
{
    private $denormalizer;

    private $promises;

    /**
     * @var array<class-string<PromiseGroupResolver>, PromiseGroupResolver>
     */
    private $resolvers = [];

    public function __construct(ResourceDenormalizer $denormalizer, PromiseCollection $promises)
    {
        $this->denormalizer = $denormalizer;
        $this->promises     = $promises;
    }

    public function addResolver(PromiseGroupResolver $resolver): void
    {
        $resolverClass = \get_class($resolver);
        if (isset($this->resolvers[$resolverClass]) === true) {
            throw new \RuntimeException(
                \sprintf('Resolver with type %s already exists in composer', $resolverClass)
            );
        }

        $this->resolvers[$resolverClass] = $resolver;
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
        $result = $this->denormalizer->denormalize($resources, $resourceType);
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
            $resolver->resolve($promiseGroup);
        }

        $this->processResources();
    }
}
