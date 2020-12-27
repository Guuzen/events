<?php

declare(strict_types=1);

namespace App\Infrastructure\ArrayComposer;

use App\Infrastructure\ArrayComposer\ResourceLink\OneToMany;
use App\Infrastructure\ArrayComposer\ResourceLink\OneToOne;

final class Schema
{
    /**
     * @var array<int, LinkParams>
     */
    private $linkParamsList = [];

    /**
     * @var array<string, array{data: array<int, array>, linkParamsList: array<int, LinkParams>}>
     */
    private $resources = [];

    public function link(LinkParams $linkParams): void
    {
        $this->linkParamsList[] = $linkParams;
    }

    public function oneToOne(
        string $leftResource,
        Path $leftPath,
        string $rightResource,
        Path $rightPath,
        string $writePath
    ): void
    {

        $this->link(new LinkParams($leftResource, $leftPath, $rightResource, $rightPath, new OneToOne(), $writePath));
    }

    public function oneToMany(
        string $leftResource,
        Path $leftPath,
        string $rightResource,
        Path $rightPath,
        string $writePath
    ): void
    {
        $this->link(new LinkParams($leftResource, $leftPath, $rightResource, $rightPath, new OneToMany(), $writePath));
    }

    /**
     * @param array<int, array> $resources
     *
     * @return array<int, array>
     */
    public function collect(array $resources, string $leftResourceId, ResourceProviders $providers): array
    {
        $resourcesBuffer = [
            [$resources, $leftResourceId]
        ];

        $this->resources[$leftResourceId] = [
            'data'           => $resources,
            'linkParamsList' => $this->linkParamsList,
        ];

        foreach ($resourcesBuffer as $i => $iValue) {
            [$bufferedResources, $bufferedLeftResourceId] = $resourcesBuffer[$i];
            $leftResourceLinkParams           = $this->getLinkParamsByLeftResourceId($bufferedLeftResourceId);
            $groupedByRightResourceLinkParams = $this->groupByRightResourceIdLinkParams($leftResourceLinkParams);
            foreach ($groupedByRightResourceLinkParams as $rightResourceId => $groupedByRightResourceLinkParam) {
                $keys                              = $this->collectLeftKeys($bufferedResources, $groupedByRightResourceLinkParam);
                $providedResources                 = $providers->provide($keys, $rightResourceId);
                $this->resources[$rightResourceId] = [
                    'data'           => $providedResources,
                    'linkParamsList' => $this->getLinkParamsByLeftResourceId($rightResourceId),
                ];
                $resourcesBuffer[]                 = [$providedResources, $rightResourceId];
            }
        }

        foreach ($this->resources as &$resource) {
            $data = &$resource['data'];
            /** @var LinkParams[] $linkParams */
            $linkParams = $resource['linkParamsList'];
            foreach ($data as &$datum) {
                foreach ($linkParams as $linkParam) {
                    $expandedResourcePaths = $linkParam->leftPath->expand($datum);
                    $groupedResources      = $linkParam->resourceLink->group(
                        $this->resources[$linkParam->rightResourceId]['data'],
                        $linkParam->rightPath
                    );
                    foreach ($expandedResourcePaths as $expandedResourcePath) {
                        $writePath = $expandedResourcePath->previousPath();
                        /** @var array $exposedResource */
                        $exposedResource = &$writePath->expose($datum);

                        /** @var array-key|null $resourceKey */
                        $resourceKey = $expandedResourcePath->expose($datum);
                        if ($resourceKey === null) {
                            /** @psalm-suppress MixedAssignment */
                            $exposedResource[$linkParam->writeField] = $linkParam->resourceLink->defaultEmptyValue();
                            continue;
                        }
                        if (isset($groupedResources[$resourceKey]) === false) {
                            /** @psalm-suppress MixedAssignment */
                            $exposedResource[$linkParam->writeField] = $linkParam->resourceLink->defaultEmptyValue();
                            continue;
                        }

                        $exposedResource[$linkParam->writeField] = $groupedResources[$resourceKey];
                    }
                }
            }
        }

        return $this->resources[$leftResourceId]['data'];
    }

    /**
     * @param array[] $items
     *
     * @return array<int, array-key>
     */
    private function extractKeys(array $items, Path $path): array
    {
        $expandedPaths = $path->expand($items);
        $keys          = [];
        foreach ($expandedPaths as $expandedPath) {
            /** @var array-key $key */
            $key    = $expandedPath->expose($items);
            $keys[] = $key;
        }

        return \array_filter($keys);
    }

    /**
     * @return array<int, LinkParams>
     */
    private function getLinkParamsByLeftResourceId(string $leftResourceId): array
    {
        $result = [];
        foreach ($this->linkParamsList as $linkParam) {
            if ($linkParam->leftResourceId === $leftResourceId) {
                $result[] = $linkParam;
            }
        }

        return $result;
    }

    /**
     * @param LinkParams[] $linkParams
     *
     * @return array<string, array<int, LinkParams>>
     */
    private function groupByRightResourceIdLinkParams(array $linkParams): array
    {
        $grouped = [];
        foreach ($linkParams as $linkParam) {
            $grouped[$linkParam->rightResourceId][] = $linkParam;
        }

        return $grouped;
    }

    /**
     * @param array[]      $items
     * @param LinkParams[] $linkParams
     *
     * @return array<int, array-key>
     */
    private function collectLeftKeys(array $items, array $linkParams): array
    {
        $keys = [];
        foreach ($linkParams as $rightResource => $linkParam) {
            $keys = [
                ...$keys,
                ...$this->extractKeys($items, $linkParam->leftPath->prependForArray()),
            ];
        }

        return $keys;
    }
}
