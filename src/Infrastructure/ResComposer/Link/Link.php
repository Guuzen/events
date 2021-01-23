<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Link;

use App\Infrastructure\ResComposer\Resource;

interface Link
{
    /**
     * @param array<int, Resource> $resources
     *
     * @return array<string, mixed>
     */
    public function group(array $resources): array;

    /**
     * @return mixed
     */
    public function defaultEmptyValue();
}
