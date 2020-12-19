<?php

declare(strict_types=1);

namespace App\Infrastructure\ArrayComposer;

interface ResourceProvider
{
    /**
     * @param array-key[] $keys
     *
     * @psalm-return array<int, array>
     */
    public function resources(array $keys): array;
}