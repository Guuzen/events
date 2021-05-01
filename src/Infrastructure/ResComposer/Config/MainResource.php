<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Config;

use App\Infrastructure\ResComposer\PromiseCollector\PromiseCollector;

/**
 * @psalm-immutable
 */
final class MainResource
{
    public $name;

    public $collector;

    public function __construct(string $name, PromiseCollector $collector)
    {
        $this->name      = $name;
        $this->collector = $collector;
    }
}
