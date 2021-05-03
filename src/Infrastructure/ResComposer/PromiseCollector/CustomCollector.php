<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\PromiseCollector;

use App\Infrastructure\ResComposer\PromiseCollector;

final class CustomCollector implements PromiseCollector
{
    private $collector;

    /**
     * @param callable(mixed): \App\Infrastructure\ResComposer\PromiseCollection\Promise[] $collector
     */
    public function __construct(callable $collector)
    {
        $this->collector = $collector;
    }

    public function collect(\ArrayObject $resource): array
    {
        return ($this->collector)($resource);
    }
}