<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer;

interface Resource
{
    /**
     * @return array<int, Promise>
     */
    public function promises(): array;
}
