<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer;

final class PromiseCollection
{
    /**
     * @var array<class-string<PromiseGroupResolver>, array<int, Promise>>
     */
    private $promises = [];

    /**
     * @param class-string<PromiseGroupResolver> $resolverId
     */
    public function remember(Promise $promise, string $resolverId): void
    {
        $this->promises[$resolverId][] = $promise;
    }

    /**
     * @return array<string, PromiseGroup>
     */
    public function release(ResourceDenormalizer $resourceDenormalizer): array
    {
        $promiseGroups = [];
        [$promises, $this->promises] = [$this->promises, []];
        foreach ($promises as $resolverId => $group) {
            $promiseGroups[$resolverId] = new PromiseGroup(
                $group,
                $resourceDenormalizer,
            );
        }

        return $promiseGroups;
    }
}
