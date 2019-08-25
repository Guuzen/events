<?php

namespace App\Common;

class Error
{
    private $error;

    final public function __construct($error = '')
    {
        $this->error = $error;
    }
}
