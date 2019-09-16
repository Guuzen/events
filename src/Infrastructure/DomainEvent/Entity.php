<?php

namespace App\Infrastructure\DomainEvent;

class Entity
{
    /**
     * @var Event[]
     */
    private $events = [];

    protected function rememberThat(Event $event): void
    {
        $this->events[] = $event;
    }

    /**
     * @return Event[]
     */
    public function releaseEvents(): array
    {
        $events       = $this->events;
        $this->events = [];

        return $events;
    }
}
