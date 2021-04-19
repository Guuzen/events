<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\PromiseCollector;

use App\Infrastructure\ResComposer\Promise;
use App\Infrastructure\ResComposer\TypeChecker;

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
                function (\ArrayObject $resource) {
                    $id = $resource[$this->readKey];
                    TypeChecker::assertThatValueIsArrayKeyOrNull($id);

                    return $id;
                },
                /** @param mixed $writeValue */
                function (\ArrayObject $resource, $writeValue): void {
                    /** @psalm-suppress MixedAssignment */
                    $resource[$this->writeKey] = $writeValue;
                },
                $resource
            )
        ];
    }
}