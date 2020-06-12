<?php

namespace App\Event\Action\CreateEvent;

use App\Event\Model\EventId;
use App\Infrastructure\Http\AppController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CreateEventHttpAdapter extends AppController
{
    private $em;

    private $handler;

    public function __construct(EntityManagerInterface $em, CreateEventHandler $handler)
    {
        $this->em      = $em;
        $this->handler = $handler;
    }

    /**
     * @Route("/admin/event/create", methods={"POST"})
     */
    public function __invoke(CreateEventRequest $createEventRequest): Response
    {
        // TODO wrap transactions on kernel events ?
        /** @var EventId $eventId */
        $eventId = $this->em->transactional(
            function () use ($createEventRequest): EventId {
                return $this->handler->createEvent($createEventRequest->toCreateEvent());
            }
        );

        // TODO how about response objects ?
        return $this->response($eventId);
    }
}
