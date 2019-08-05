<?php

namespace App\Queries\FindEvents;

use App\Common\AppController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// TODO REMOVE one controller App or Base

/**
 * @Route("/admin/event/list", methods={"GET"})
 */
final class FindEventsHttpAdapter extends AppController
{
    /**
     * @var FindEvents
     */
    private $findEvents;

    public function __construct(FindEvents $findEvents)
    {
        $this->findEvents = $findEvents;
    }

    public function __invoke(): Response
    {
        $events = ($this->findEvents)();

        return $this->successJson($events);
    }
}
