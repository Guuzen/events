<?php

declare(strict_types=1);

namespace App\Infrastructure\ArrayComposer\ResourceLink;

use App\Infrastructure\ArrayComposer\Path\Path;

interface ResourceLink
{
    /**
     * @param array[] $resources
     *
     * @return array<array-key, array<array-key, mixed>>
     */
    public function group(array $resources, Path $path): array;

    /**
     * @return mixed
     */
    public function defaultEmptyValue();
}
