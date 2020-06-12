<?php

namespace App\Event\Action\CreateEvent;

// TODO validation
use App\Infrastructure\Http\RequestResolver\AppRequest;

/**
 * @psalm-immutable
 */
final class CreateEventRequest implements AppRequest
{
    private $name;

    private $domain;

    public function __construct(string $name, string $domain)
    {
        $this->name   = $name;
        $this->domain = $domain;
    }

    public function toCreateEvent(): CreateEvent
    {
        return new CreateEvent($this->name, $this->domain);
    }
}
