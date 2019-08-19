<?php

namespace App\Tests\AppResponse\EventById;

final class EventByIdBuilder
{
    private $id;

    private $name;

    private $domain;

    private function __construct(?string $id, string $name, string $domain)
    {
        $this->id     = $id;
        $this->name   = $name;
        $this->domain = $domain;
    }

    public static function any(): self
    {
        return new self(null, '2019 foo event', '2019foo.event.com');
    }

    public function build(): EventById
    {
        return new EventById($this->id, $this->name, $this->domain);
    }

    public function withId(string $id): self
    {
        return new self($id, $this->name, $this->domain);
    }

    public function withName(string $name): self
    {
        return new self($this->id, $name, $this->domain);
    }

    public function withDomain(string $domain): self
    {
        return new self($this->id, $this->name, $domain);
    }
}
