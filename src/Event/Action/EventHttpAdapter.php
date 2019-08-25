<?php

namespace App\Event\Action;

use App\Common\AppController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class EventHttpAdapter extends AppController
{
    /**
     * @Route("/admin/event/create", methods={"POST"})
     */
    public function createEvent(CreateEvent $createEvent, EventHandler $handler): Response
    {
        $eventId = $handler->createEvent($createEvent);

        // TODO how about response objects ?
        return $this->successJson($eventId);
    }
}
