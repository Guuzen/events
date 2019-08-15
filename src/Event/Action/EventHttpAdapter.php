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
        [$eventId, $error] = $handler->createEvent($createEvent);

        if ($error) {
            return $this->errorJson($error);
        }

        // TODO how about response objects ?
        return $this->successJson($eventId);
    }
}
