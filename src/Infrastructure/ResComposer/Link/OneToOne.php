<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Link;

final class OneToOne implements Link
{
    private $rightKey;

    public function __construct(string $rightKey)
    {
        $this->rightKey = $rightKey;
    }

    public function group(array $resources): array
    {
        $map      = [];
        $rightKey = $this->rightKey;
        foreach ($resources as $resource) {
            /** @psalm-suppress MixedAssignment */
            $mapId = $resource[$rightKey];
            if (\is_string($mapId) === false) {
                throw new \RuntimeException(
                    \sprintf('Resource group key must be a string %s given', \gettype($mapId))
                );
            }
            $map[$mapId] = $resource;
        }

        return $map;
    }

    public function defaultEmptyValue(): void
    {
    }
}
