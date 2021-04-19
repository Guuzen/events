<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Link;

final class OneToMany implements Link
{
    private $rightKey;

    public function __construct(string $rightKey)
    {
        $this->rightKey = $rightKey;
    }

    public function group(array $resources): array
    {
        $groups   = [];
        $rightKey = $this->rightKey;
        foreach ($resources as $resource) {
            /** @psalm-suppress MixedAssignment */
            $groupId = $resource[$rightKey];
            if (\is_string($groupId) === false) {
                throw new \RuntimeException(
                    \sprintf('Resource group key must be a string %s given', \gettype($groupId))
                );
            }
            $groups[$groupId][] = $resource;
        }

        return $groups;
    }

    public function defaultEmptyValue(): array
    {
        return [];
    }
}
