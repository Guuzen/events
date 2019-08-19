<?php

namespace App\Tests\AppRequest\Event;

final class CreateEvent
{
    private $name;

    private $domain;

    public function __construct(string $name, string $domain)
    {
        $this->name   = $name;
        $this->domain = $domain;
    }
}
