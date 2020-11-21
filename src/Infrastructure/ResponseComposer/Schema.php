<?php

declare(strict_types=1);

namespace App\Infrastructure\ResponseComposer;

use App\Infrastructure\ResponseComposer\ResourceLink\OneToMany;
use App\Infrastructure\ResponseComposer\ResourceLink\OneToOne;
use App\Infrastructure\ResponseComposer\ResourceLink\ResourceLink;
use App\Infrastructure\ResponseComposer\ResponseBuilder\GroupBuilder;
use App\Infrastructure\ResponseComposer\ResponseBuilder\SingleBuilder;

/**
 * @psalm-type LeftKeyExtractor = pure-callable(mixed): ?string
 * @psalm-type RightKeyExtractor = pure-callable(mixed): string
 * @psalm-type Resource = object|array
 */
final class Schema
{
    /**
     * @psalm-var class-string
     */
    private string $class;

    /**
     * @psalm-var array<int, array{0: self, 1: LeftKeyExtractor, 2: ResourceLink}>
     */
    private array $linkParams = [];

    /**
     * @psalm-param class-string $class
     */
    public function __construct(string $class)
    {
        $this->class = $class;
    }

    /**
     * @psalm-param LeftKeyExtractor $leftKeyExtractor
     * @psalm-param RightKeyExtractor $rightKeyExtractor
     */
    public function link(Schema $schema, callable $leftKeyExtractor, ResourceLink $link): void
    {
        $this->linkParams[] = [$schema, $leftKeyExtractor, $link];
    }

    /**
     * @psalm-param class-string $class
     * @psalm-param LeftKeyExtractor $leftKeyExtractor
     * @psalm-param RightKeyExtractor $rightKeyExtractor
     */
    public function oneToOne(Schema $schema, callable $leftKeyExtractor, callable $rightKeyExtractor): void
    {
        $this->link($schema, $leftKeyExtractor, new OneToOne($rightKeyExtractor));
    }

    /**
     * @psalm-param class-string $class
     * @psalm-param LeftKeyExtractor $leftKeyExtractor
     * @psalm-param RightKeyExtractor $rightKeyExtractor
     */
    public function oneToMany(Schema $schema, callable $leftKeyExtractor, callable $rightKeyExtractor): void
    {
        $this->link($schema, $leftKeyExtractor, new OneToMany($rightKeyExtractor));
    }

    /**
     * @psalm-param array<array-key, Resource> $resources
     */
    public function collect(array $resources, ResourceProviders $providers): GroupBuilder
    {
        $groupedBuilders = [];
        foreach ($this->linkParams as $linkParam) {
            /** @var ResourceLink $link */
            /** @var self $schema */
            [$schema, $leftKeyExtractor, $link] = $linkParam;
            $provider          = $providers->provider($schema->class);
            $linkedResources   = $provider->resources(
                $this->extractKeys($resources, $leftKeyExtractor)
            );
            $groupedBuilder    = $schema->collect(
                $linkedResources,
                $providers
            );
            $groupedBuilders[] = $groupedBuilder->group($link);
        }

        $builders = [];
        foreach ($resources as $resource) {
            $linkedBuilders = [];
            foreach ($this->linkParams as $linkIndex => $linkParam) {
                [1 => $leftKeyExtractor, 2 => $link] = $linkParam;
                $resourceKey = $leftKeyExtractor($resource);
                if ($resourceKey === null) {
                    $linkedBuilders[] = $link->defaultEmptyValue();
                    continue;
                }
                if (isset($groupedBuilders[$linkIndex][$resourceKey]) === false) {
                    $linkedBuilders[] = $link->defaultEmptyValue();
                    continue;
                }
                $linkedBuilders[] = $groupedBuilders[$linkIndex][$resourceKey];
            }
            $builders[] = new SingleBuilder($this->class, $resource, $linkedBuilders);
        }

        return new GroupBuilder($builders);
    }

    /**
     * @psalm-param array<array-key, Resource> $items
     * @psalm-param LeftKeyExtractor $keyExtractor
     *
     * @return string[]
     */
    private function extractKeys(array $items, callable $keyExtractor): array
    {
        $result = [];
        foreach ($items as $item) {
            $result[] = $keyExtractor($item);
        }

        return \array_filter($result);
    }
}
