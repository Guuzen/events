<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Link;

interface Link
{
    /**
     * @param array<array-key, \ArrayObject> $resources
     *
     * @return array<array-key, mixed>
     */
    public function group(array $resources): array;

    /**
     * @return mixed
     */
    public function defaultEmptyValue();
}
