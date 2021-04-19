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
        /** @psalm-suppress MixedAssignment */
        foreach ($resource[$this->arrayOfKeys] as $index => $key) {
            /** @psalm-suppress MixedArgumentTypeCoercion */
            $promises[] = new Promise(
            /** @psalm-suppress MissingClosureReturnType */
                fn(\ArrayObject $resource) => $key,
                /** @param mixed $writeValue */
                function (\ArrayObject $customer, $writeValue) use ($index): void {
                    /**
                     * @psalm-suppress MixedAssignment
                     * @psalm-suppress MixedArrayAssignment
                     * @psalm-suppress MixedArrayOffset
                     */
                    $customer[$this->writeKey][$index] = $writeValue;
                },
                $resource
            );
        }

        return $promises;
    }
}
