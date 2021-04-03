<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer;

interface Resource
{
    /**
     * @return class-string<PromiseGroupResolver>[]
     */
    public static function resolvers(): array;
}
