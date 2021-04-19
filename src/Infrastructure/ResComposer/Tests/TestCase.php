<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests;

use App\Infrastructure\ResComposer\ResourceComposer;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ResourceComposer
     */
    protected $composer;

    protected function setUp(): void
    {
        $this->composer = new ResourceComposer();
    }

    abstract public function test(): void;
}
