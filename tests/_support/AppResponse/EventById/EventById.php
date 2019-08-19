<?php

namespace App\Tests\AppResponse\EventById;

final class EventById implements \JsonSerializable
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

    public function jsonSerialize(): array
    {
        return [
            'id'     => $this->id,
            'name'   => $this->name,
            'domain' => $this->domain,
        ];
    }
}
