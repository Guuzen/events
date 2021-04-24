<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\PromiseCollector;

use App\Infrastructure\ResComposer\Promise;

final class MultipleSimpleCollector implements PromiseCollector
{
    private $keys;

    /**
     * @param array{0: string, 1: string}[] $keys
     */
    public function __construct(array $keys)
    {
        $this->keys = $keys;
    }

    public function collect(\ArrayObject $resource): array
    {
        $promises = [];
        foreach ($this->keys as [$readKey, $writeKey]) {
            $promises[] = new Promise(
            /** @psalm-suppress MixedInferredReturnType */
                function (\ArrayObject $resource) use ($readKey): string|int|null {
                    /** @psalm-suppress MixedReturnStatement */
                    return $resource[$readKey];
                },
                function (\ArrayObject $resource, mixed $writeValue) use ($writeKey): void {
                    /** @psalm-suppress MixedAssignment */
                    $resource[$writeKey] = $writeValue;
                },
                $resource
            );
        }

        return $promises;
    }
}
