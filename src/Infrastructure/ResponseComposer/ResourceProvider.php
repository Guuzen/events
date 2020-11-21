<?php

declare(strict_types=1);

namespace App\Infrastructure\ResponseComposer;

/**
 * @psalm-import-type Resource from \App\Infrastructure\ResponseComposer\Schema
 */
interface ResourceProvider
{
    /**
     * @param string[] $keys
     *
     * @psalm-return Resource[]
     */
    public function resources(array $keys): array;
}
