<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\PromiseCollector;

use App\Infrastructure\ResComposer\Promise;
use App\Infrastructure\ResComposer\TypeChecker;

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
                function (\ArrayObject $resource) use ($readKey) {
                    TypeChecker::assertThatValueIsArrayKeyOrNull($resource[$readKey]);

                    return $resource[$readKey];
                },
                /** @param mixed $writeValue */
                function (\ArrayObject $resource, $writeValue) use ($writeKey): void {
                    /** @psalm-suppress MixedAssignment */
                    $resource[$writeKey] = $writeValue;
                },
                $resource
            );
        }

        return $promises;
    }
}
