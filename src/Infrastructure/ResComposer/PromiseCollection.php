<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer;

final class PromiseCollection
{
    /**
     * @var array<int, Promise>
     */
    private $promises = [];

    public function remember(Promise $promise): void
    {
        $this->promises[] = $promise;
    }

    /**
     * @return array<int, Promise>
     */
    public function release(): array
    {
        [$promises, $this->promises] = [$this->promises, []];

        return $promises;
    }
}
