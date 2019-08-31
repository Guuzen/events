<?php

namespace App\Queries\Event\FindEventsInList;

final class EventInList
{
    /**
     * @readonly
     */
    public $id;

    /**
     * @readonly
     */
    public $name;

    /**
     * @readonly
     */
    public $domain;

    private function __construct(string $id, string $name, string $domain)
    {
        $this->id     = $id;
        $this->name   = $name;
        $this->domain = $domain;
    }
}
