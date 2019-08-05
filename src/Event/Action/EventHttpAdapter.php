<?php

namespace App\Event\Action;

use App\Common\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class EventHttpAdapter extends BaseController
{
    /**
     * @Route("/admin/event/create", methods={"POST"})
     */
    public function createEvent(CreateEvent $createEvent, EventHandler $handler): Response
    {
        [$eventId, $error] = $handler->createEvent($createEvent);

        if ($error) {
            return $this->jsonError($error);
        }

        // TODO how about response objects ?
        return $this->jsonSuccess(['id' => (string) $eventId]);
    }
}
