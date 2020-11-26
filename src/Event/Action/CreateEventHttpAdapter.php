<?php

namespace App\Event\Action;

use App\Event\Model\Event;
use App\Event\Model\EventId;
use App\Event\Model\Events;
use App\Infrastructure\Http\AppController\AppController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CreateEventHttpAdapter extends AppController
{
    private $events;

    public function __construct(Events $events)
    {
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
        $this->flush();

        // TODO how about response objects ?
        return $this->response($eventId);
    }
}
