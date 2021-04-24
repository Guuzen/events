<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\PromiseCollector;

use App\Infrastructure\ResComposer\Promise;

final class ArrayCollector implements PromiseCollector
{
    private $arrayOfKeys;

    private $writeKey;

    public function __construct(string $arrayOfKeys, string $writeKey)
    {
        $this->arrayOfKeys = $arrayOfKeys;
        $this->writeKey    = $writeKey;
    }

    public function collect(\ArrayObject $resource): array
    {
        $promises = [];

        /** @var array<array-key,array-key> $keys */
        $keys = $resource[$this->arrayOfKeys];
        foreach ($keys as $index => $key) {
            $promises[] = new Promise(
            /** @psalm-suppress MixedInferredReturnType */
                function (\ArrayObject $resource) use ($key): string|int {
                    /** @psalm-suppress MixedReturnStatement */
                    return $key;
                },
                function (\ArrayObject $customer, mixed $writeValue) use ($index): void {
                    /**
                     * @psalm-suppress MixedArrayAssignment
                     * @psalm-suppress MixedAssignment
                     */
                    $customer[$this->writeKey][$index] = $writeValue;
                },
                $resource
            );
        }

        return $promises;
    }
}
