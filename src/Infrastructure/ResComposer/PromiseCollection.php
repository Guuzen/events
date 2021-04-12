<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer;

final class PromiseCollection
{
    /**
     * @var array<int, array<int, Promise>>
     */
    private $promises = [];

    public function remember(Promise $promise, int $resolverId): void
    {
        $this->promises[$resolverId][] = $promise;
    }

    /**
     * @return array<int, PromiseGroup>
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
