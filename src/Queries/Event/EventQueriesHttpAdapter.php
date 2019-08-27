<?php

namespace App\Queries\Event;

use App\Infrastructure\Http\AppController;
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
    public function findAll(): Response
    {
        $events = $this->eventQueries->findAll();

        return $this->successJson($events);
    }

    /**
     * @Route("/show")
     */
    public function findById(FindEventById $findEventById): Response
    {
        $event = $this->eventQueries->findEventById($findEventById->eventId);

        return $this->successJson($event);
    }
}
