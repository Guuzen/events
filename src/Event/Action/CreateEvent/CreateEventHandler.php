<?php

namespace App\Event\Action\CreateEvent;

use App\Event\Model\Event;
use App\Event\Model\EventId;
use App\Event\Model\Events;

final class CreateEventHandler
{
    private $events;

    public function __construct(Events $events)
    {
        $this->events = $events;
    }

    public function createEvent(): EventId
    {
        $eventId = EventId::new();
        $event   = new Event($eventId);
        $this->events->add($event);

        return $eventId;
    }
}
