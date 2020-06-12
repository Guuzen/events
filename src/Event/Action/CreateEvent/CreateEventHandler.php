<?php

namespace App\Event\Action\CreateEvent;

use App\Event\Model\Event;
use App\Event\Model\EventConfig;
use App\Event\Model\EventId;
use App\Event\Model\Events;
use Doctrine\ORM\EntityManagerInterface;

final class CreateEventHandler
{
    private $em;

    private $events;

    public function __construct(EntityManagerInterface $em, Events $events)
    {
        $this->em     = $em;
        $this->events = $events;
    }

    public function createEvent(CreateEvent $createEvent): EventId
    {
        $eventId = EventId::new();
        $event   = new Event($eventId);
        $this->events->add($event);

        $eventConfig = new EventConfig(
            (string)$eventId,
            $createEvent->name,
            $createEvent->domain
        );
        $this->em->persist($eventConfig);

        return $eventId;
    }
}
