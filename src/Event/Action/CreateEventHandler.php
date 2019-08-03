<?php

namespace App\Event\Action;

use App\Event\Model\Event;
use App\Event\Model\EventConfig;
use App\Event\Model\EventId;
use App\Event\Model\Events;
use Doctrine\ORM\EntityManagerInterface;

final class CreateEventHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var Events
     */
    private $events;

    public function __construct(EntityManagerInterface $em, Events $events)
    {
        $this->em     = $em;
        $this->events = $events;
    }

    public function createEvent(CreateEvent $createEvent): array
    {
        $eventId = EventId::new();
        $event   = new Event($eventId);
        $this->events->add($event);

        $eventConfig         = new EventConfig();
        $eventConfig->id     = $eventId;
        $eventConfig->name   = $createEvent->name;
        $eventConfig->domain = $createEvent->domain;
        $this->em->persist($eventConfig);

        $this->em->flush();

        return [$eventId, null];
    }
}
