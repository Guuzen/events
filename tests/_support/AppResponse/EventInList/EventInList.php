<?php

namespace App\Tests\AppResponse\EventInList;

final class EventInList
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
