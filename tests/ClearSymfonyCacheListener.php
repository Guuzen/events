<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Runner\BeforeFirstTestHook;

final class ClearSymfonyCacheListener implements BeforeFirstTestHook
{
    public function executeBeforeFirstTest(): void
    {
        $cachePath = __DIR__ . '/../var/cache';

        \exec("rm -rf $cachePath");
    }
}