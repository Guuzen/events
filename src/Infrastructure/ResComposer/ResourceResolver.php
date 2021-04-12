<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer;

use App\Infrastructure\ResComposer\Link\Link;

/**
 * @psalm-immutable
 */
final class ResourceResolver
{
    public $resource;

    public $link;

    public $relatedResource;

    public $loader;

    public $resolver;

    /**
     * @param class-string                     $resource
     * @param class-string                     $relatedResource
     * @param class-string<ResourceDataLoader> $loader
     * @param callable(mixed): Promise[] $resolver
     */
    public function __construct(string $resource, Link $link, string $relatedResource, string $loader, callable $resolver)
    {
        $this->resource        = $resource;
        $this->link            = $link;
        $this->relatedResource = $relatedResource;
        $this->loader          = $loader;
        $this->resolver        = $resolver;
    }
}