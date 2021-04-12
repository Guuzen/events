<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Link;

interface Link
{
    /**
     * @param array<int, object> $resources
     *
     * @return array<string, mixed>
     */
    public function group(array $resources): array;

    /**
     * @return mixed
     */
    public function defaultEmptyValue();
}
