<?php

namespace App\Common\Result;

class Ok implements Result
{
    private $value;

    final public function __construct($value = null)
    {
        $this->value = $value;
    }

    final public function isOk(): bool
    {
        return true;
    }

    final public function isErr(): bool
    {
        return false;
    }

    final public function value()
    {
        return $this->value;
    }

    final public function error()
    {
        throw new \LogicException('Check is result error before!');
    }
}
