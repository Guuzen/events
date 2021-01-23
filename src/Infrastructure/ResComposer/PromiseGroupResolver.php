<?php
declare(strict_types=1);

namespace App\Infrastructure\ResComposer;

interface PromiseGroupResolver
{
    public function resolve(PromiseGroup $promises): void;
}
