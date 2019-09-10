<?php

namespace App\Queries\Event\FindEventById;

/**
 * @psalm-immutable
 */
final class EventById
{
    public $id;

    public $name;

    public $domain;

    private function __construct(string $id, string $name, string $domain)
    {
        $this->id     = $id;
        $this->name   = $name;
        $this->domain = $domain;
    }
}
