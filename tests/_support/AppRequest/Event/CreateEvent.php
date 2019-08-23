<?php

namespace App\Tests\AppRequest\Event;

final class CreateEvent
{
    private $name;

    private $domain;

    private function __construct(string $name, string $domain)
    {
        $this->name   = $name;
        $this->domain = $domain;
    }

    public static function any(): self
    {
        return new self('2019 foo event', '2019foo.event.com');
    }
}
