<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\PromiseCollector;

use App\Infrastructure\ResComposer\Promise;

final class SimpleCollector implements PromiseCollector
{
    private $readKey;

    private $writeKey;

    public function __construct(string $readKey, string $writeKey)
    {
        $this->readKey  = $readKey;
        $this->writeKey = $writeKey;
    }

    public function collect(\ArrayObject $resource): array
    {
        return [
            new Promise(
            /** @psalm-suppress MixedInferredReturnType */
                function (\ArrayObject $resource): string|int|null {
                    /** @psalm-suppress MixedReturnStatement */
                    return $resource[$this->readKey];
                },
                function (\ArrayObject $resource, mixed $writeValue): void {
                    /** @psalm-suppress MixedAssignment */
                    $resource[$this->writeKey] = $writeValue;
                },
                $resource
            )
        ];
    }
}