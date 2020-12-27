<?php

declare(strict_types=1);

namespace App\Infrastructure\ArrayComposer\ResourceLink;

use App\Infrastructure\ArrayComposer\Path;

final class OneToOne implements ResourceLink
{
    public function group(array $resources, Path $path): array
    {
        $map = [];
        foreach ($resources as $resource) {
            /** @var array-key $key */
            $key       = $path->expose($resource);
            $map[$key] = $resource;
        }

        return $map;
    }

    public function defaultEmptyValue(): void
    {
    }
}
