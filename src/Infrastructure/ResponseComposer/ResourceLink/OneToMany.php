<?php

declare(strict_types=1);

namespace App\Infrastructure\ResponseComposer\ResourceLink;

use App\Infrastructure\ResponseComposer\ResponseBuilder\GroupBuilder;
use App\Infrastructure\ResponseComposer\ResponseBuilder\ResponseBuilder;

/**
 * @psalm-import-type RightKeyExtractor from \App\Infrastructure\ResponseComposer\Schema
 */
final class OneToMany implements ResourceLink
{
    /**
     * @psalm-var RightKeyExtractor
     */
    private $keyExtractor;

    /**
     * @psalm-param RightKeyExtractor $keyExtractor
     */
    public function __construct(callable $keyExtractor)
    {
        $this->keyExtractor = $keyExtractor;
    }

    /**
     * @inheritDoc
     */
    public function group(array $resources): array
    {
        $groups = [];
        foreach ($resources as $builder) {
            $key            = $builder->extractKey($this->keyExtractor);
            $groups[$key][] = $builder;
        }

        $map = [];
        foreach ($groups as $key => $group) {
            $map[$key] = new GroupBuilder($group);
        }

        return $map;
    }

    public function defaultEmptyValue(): ResponseBuilder
    {
        return new GroupBuilder([]);
    }
}
