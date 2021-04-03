<?php
declare(strict_types=1);

namespace App\Infrastructure\ResComposer;

interface PromiseGroupResolver
{
    public function resolve(PromiseGroup $promises): void;

    /**
     * @template T of object
     *
     * @param T $resource
     *
     * @return Promise[]
     */
    public static function collectPromises(object $resource): array;
}
