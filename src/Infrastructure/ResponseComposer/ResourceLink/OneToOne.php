<?php

declare(strict_types=1);

namespace App\Infrastructure\ResponseComposer\ResourceLink;

use App\Infrastructure\ResponseComposer\ResponseBuilder\NullBuilder;
use App\Infrastructure\ResponseComposer\ResponseBuilder\ResponseBuilder;

/**
 * @psalm-import-type RightKeyExtractor from \App\Infrastructure\ResponseComposer\Schema
 */
final class OneToOne implements ResourceLink
{
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
        $map = [];
        foreach ($resources as $builder) {
            $key       = $builder->extractKey($this->keyExtractor);
            $map[$key] = $builder;
        }

        return $map;
    }

    public function defaultEmptyValue(): ResponseBuilder
    {
        return new NullBuilder();
    }
}
