<?php

namespace App\Event\Action;

use App\Event\Model\Event;
use App\Event\Model\EventId;
use App\Event\Model\Events;
use Doctrine\ORM\EntityManagerInterface;

final class CreateEventHandler
{
    private $events;

    private $em;

    public function __construct(Events $events, EntityManagerInterface $em)
    {
        $this->events = $events;
        $this->em     = $em;
    }

    public function handle(EventId $eventId): void
    {
        $event = new Event($eventId);
        $this->events->add($event);

        // TODO wrap transactions on kernel events ?
        $this->em->flush();

        // TODO how about response objects ?
    }
}
