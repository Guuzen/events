<?php

namespace App\Common;

class Error
{
    private $error;

    final public function __construct(string $error = '')
    {
        $this->error = $error;
    }
}
