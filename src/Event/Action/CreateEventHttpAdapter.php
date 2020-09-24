<?php

namespace App\Event\Action;

use App\Event\Model\Event;
use App\Event\Model\EventId;
use App\Event\Model\Events;
use App\Infrastructure\Http\AppController\AppController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CreateEventHttpAdapter extends AppController
{
    private $em;

    private $events;

    public function __construct(EntityManagerInterface $em, Events $events)
    {
        $this->em     = $em;
        $this->events = $events;
    }

    /**
     * @Route("/admin/event", methods={"POST"})
     */
    public function __invoke(): Response
    {
        $eventId = EventId::new();
        $event   = new Event($eventId);
        $this->events->add($event);

        // TODO wrap transactions on kernel events ?
        $this->em->flush();

        // TODO how about response objects ?
        return $this->response($eventId);
    }
}
