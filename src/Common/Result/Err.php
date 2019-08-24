<?php

namespace App\Common\Result;

class Err implements Result
{
    private $error;

    final public function __construct($error = '')
    {
        $this->error = $error;
    }

    final public function isOk(): bool
    {
        return false;
    }

    final public function isErr(): bool
    {
        return true;
    }

    final public function value()
    {
        throw new \LogicException('Check is result ok before!');
    }

    final public function error()
    {
        return $this->error;
    }
}
