<?php

declare(strict_types=1);

namespace App\Infrastructure\ArrayComposer\ResourceLink;

use App\Infrastructure\ArrayComposer\Path\Path;

final class OneToMany implements ResourceLink
{
    public function group(array $resources, Path $path): array
    {
        $groups = [];
        foreach ($resources as $resource) {
            /** @var array-key $key */
            $key            = $path->expose($resource);
            $groups[$key][] = $resource;
        }

        return $groups;
    }

    public function defaultEmptyValue(): array
    {
        return [];
    }
}
