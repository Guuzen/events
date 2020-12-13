<?php

declare(strict_types=1);

namespace App\Infrastructure\ArrayComposer;

use App\Infrastructure\ArrayComposer\Path\Path;
use App\Infrastructure\ArrayComposer\ResourceLink\OneToMany;
use App\Infrastructure\ArrayComposer\ResourceLink\OneToOne;
use App\Infrastructure\ArrayComposer\ResourceLink\ResourceLink;

final class Schema
{
    private $providerId;

    /**
     * @psalm-var array<int, array{0: self, 1: ResourceLink, 2: Path, 3: Path, 4: string}>
     */
    private array $linkParams = [];

    public function __construct(string $providerId)
    {
        $this->providerId = $providerId;
    }

    public function link(Schema $schema, ResourceLink $link, Path $leftPath, Path $rightPath, string $writeField): void
    {
        $this->linkParams[] = [$schema, $link, $leftPath, $rightPath, $writeField];
    }

    public function oneToOne(Schema $schema, Path $leftPath, Path $rightPath, string $writeField): void
    {

        $this->link($schema, new OneToOne(), $leftPath, $rightPath, $writeField);
    }

    public function oneToMany(Schema $schema, Path $leftPath, Path $rightPath, string $writeField): void
    {
        $this->link($schema, new OneToMany(), $leftPath, $rightPath, $writeField);
    }

    /**
     * @param array<int, array> $resources
     *
     * @return array<int, array>
     */
    public function collect(array $resources, ResourceProviders $providers): array
    {
        $groupedResources = [];
        foreach ($this->linkParams as $linkParam) {
            /** @var ResourceLink $link */
            /** @var self $schema */
            /** @var Path $leftPath */
            [$schema, $link, $leftPath, $rightPath] = $linkParam;
            $provider            = $providers->provider($schema->providerId);
            $linkedResourcesKeys = $this->extractKeys(
                $resources,
                $leftPath->prependForArray()
            );
            $linkedResources     = $schema->collect(
                $provider->resources($linkedResourcesKeys),
                $providers
            );
            $groupedResources[]  = $link->group($linkedResources, $rightPath);
        }

        $newResources = [];
        foreach ($resources as $resource) {
            $newResource = $resource;
            foreach ($this->linkParams as $linkIndex => $linkParam) {
                [1 => $link, 2 => $leftPath, 4 => $writeField] = $linkParam;

                $resourceKeyPaths = $leftPath->unwrap($resource);

                foreach ($resourceKeyPaths as $resourceKeyPath) {
                    $previousPath = $resourceKeyPath->previousPath();
                    /** @var array $exposedResource */
                    $exposedResource = &$previousPath->expose($newResource);

                    /** @var array-key|null $resourceKey */
                    $resourceKey = $resourceKeyPath->expose($resource);
                    if ($resourceKey === null) {
                        /** @psalm-suppress MixedAssignment */
                        $exposedResource[$writeField] = $link->defaultEmptyValue();
                        continue;
                    }
                    if (isset($groupedResources[$linkIndex][$resourceKey]) === false) {
                        /** @psalm-suppress MixedAssignment */
                        $exposedResource[$writeField] = $link->defaultEmptyValue();
                        continue;
                    }

                    $exposedResource[$writeField] = $groupedResources[$linkIndex][$resourceKey];
                }
            }
            $newResources[] = $newResource;
        }

        return $newResources;
    }

    /**
     * @param array[] $items
     *
     * @return array<int, array-key>
     */
    private function extractKeys(array $items, Path $path): array
    {
        $transformedPaths = $path->unwrap($items);
        $keys             = [];
        foreach ($transformedPaths as $transformedPath) {
            /** @var array-key $key */
            $key    = $transformedPath->expose($items);
            $keys[] = $key;
        }

        return \array_filter($keys);
    }
}
