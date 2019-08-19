<?php

namespace App\Tests\AppResponse\EventById;

final class EventById
{
    private $id;

    private $name;

    private $domain;

    public function __construct(string $id, string $name, string $domain)
    {
        $this->id     = $id;
        $this->name   = $name;
        $this->domain = $domain;
    }
}
