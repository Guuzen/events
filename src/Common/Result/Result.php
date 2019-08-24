<?php

namespace App\Common\Result;

interface Result
{
    public function isOk(): bool;

    public function isErr(): bool;

    public function value();

    public function error();
}
