<?php

namespace App\Queries\Event\FindEventById;

final class EventById
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
