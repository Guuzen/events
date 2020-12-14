<?php

declare(strict_types=1);

namespace App\Queries\Event\CreateEvent;

use App\Event\Action\CreateEventHandler;
use App\Event\Model\EventId;
use App\EventDomain\Action\CreateEventDomainHandler;
use App\EventDomain\Model\EventDomain;
use App\Infrastructure\Http\AppController\AppController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CreateEventHttpAdapter extends AppController
{
    private $createEventHandler;

    private $createEventDomainHandler;

    public function __construct(
        CreateEventHandler $createEventHandler,
        CreateEventDomainHandler $createEventDomainHandler
    )
    {
        $this->createEventHandler       = $createEventHandler;
        $this->createEventDomainHandler = $createEventDomainHandler;
    }

    /**
     * @Route("/admin/event", methods={"POST"})
     */
    public function __invoke(CreateEventRequest $request): Response
    {
        $eventId = EventId::new();

        $this->createEventHandler->handle($eventId);

        $this->createEventDomainHandler->handle(
            new EventDomain(
                (string)$eventId,
                $request->name,
                $request->domain
            )
        );

        return $this->response($eventId);
    }
}