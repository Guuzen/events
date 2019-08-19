<?php

namespace App\Tests\AppRequest\Event;

final class CreateEventBuilder
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

    public function build(): CreateEvent
    {
        return new CreateEvent($this->name, $this->domain);
    }

    public function withName(string $name): self
    {
        return new self($name, $this->domain);
    }

    public function withDomain(string $domain): self
    {
        return new self($this->name, $domain);
    }
}
