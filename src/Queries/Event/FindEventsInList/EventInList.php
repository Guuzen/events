<?php

namespace App\Queries\Event\FindEventsInList;

/**
 * @psalm-immutable
 */
final class EventInList
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
