<?php

declare(strict_types=1);

namespace spec\Common;

final class ObjectWithFooProperty
{
    private $foo;

    public function __construct($foo)
    {
        $this->foo = $foo;
    }
}
