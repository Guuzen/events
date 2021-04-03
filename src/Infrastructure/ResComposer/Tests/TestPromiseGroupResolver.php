<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests;

use App\Infrastructure\ResComposer\Link\Link;
use App\Infrastructure\ResComposer\PromiseGroup;
use App\Infrastructure\ResComposer\PromiseGroupResolver;
use App\Infrastructure\ResComposer\Resource;
use App\Infrastructure\ResComposer\ResourceDataLoader;

abstract class TestPromiseGroupResolver implements PromiseGroupResolver
{
    private $loader;
    private $link;
    private $resourceType;

    /**
     * @param class-string<Resource> $resourceType
     */
    public function __construct(ResourceDataLoader $loader, Link $link, string $resourceType)
    {
        $this->loader       = $loader;
        $this->link         = $link;
        $this->resourceType = $resourceType;
    }

    public function resolve(PromiseGroup $promises): void
    {
        $promises->resolve($this->loader, $this->link, $this->resourceType);
    }
}
