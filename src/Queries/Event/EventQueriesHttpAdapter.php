<?php

namespace App\Queries\Event;

use App\Infrastructure\Http\AppController;
use App\Queries\Event\FindEventById\FindEventById;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/event", methods={"GET"})
 */
final class EventQueriesHttpAdapter extends AppController
{
    private $eventQueries;

    public function __construct(EventQueries $eventQueries)
    {
        $this->eventQueries = $eventQueries;
    }

    /**
     * @Route("/list")
     */
    public function findInList(): Response
    {
        $events = $this->eventQueries->findInList();

        return $this->response($events);
    }

    /**
     * @Route("/show")
     */
    public function findById(FindEventById $findEventById): Response
    {
        $event = $this->eventQueries->findById($findEventById->eventId);

        return $this->response($event);
    }
}
