<?php

namespace App\Tests\AppRequest\Event;

final class Event implements \JsonSerializable
{
    private $name;

    private $domain;

    public function __construct(string $name, string $domain)
    {
        $this->name   = $name;
        $this->domain = $domain;
    }

    public function jsonSerialize(): array
    {
        return [
            'name'   => $this->name,
            'domain' => $this->domain,
        ];
    }
}
