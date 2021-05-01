<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\PromiseCollector;

final class MergeCollector implements PromiseCollector
{
    private $collectors;

    /**
     * @param PromiseCollector[] $collectors
     */
    public function __construct(array $collectors)
    {
        $this->collectors = $collectors;
    }

    public function collect(\ArrayObject $resource): array
    {
        $promises = [];
        foreach ($this->collectors as $collector) {
            $promises = \array_merge($promises, $collector->collect($resource));
        }

        return $promises;
    }
}
