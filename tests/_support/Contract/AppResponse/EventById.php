<?php

namespace App\Tests\Contract\AppResponse;

final class EventById
{
    private $id;

    private $name;

    private $domain;

    private function __construct(string $id, string $name, string $domain)
    {
        $this->id     = $id;
        $this->name   = $name;
        $this->domain = $domain;
    }

    public static function anyWith(string $eventId): self
    {
        return new self($eventId, '2019 foo event', '2019foo.event.com');
    }
}
