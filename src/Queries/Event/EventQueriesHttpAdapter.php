<?php

namespace App\Queries\Event;

use App\Common\AppController;
use Symfony\Component\HttpFoundation\Request;
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
    public function findById(Request $request): Response
    {
        $eventId = $request->get('event_id');
        $event   = $this->eventQueries->findById($eventId);

        return $this->successJson($event);
    }
}
