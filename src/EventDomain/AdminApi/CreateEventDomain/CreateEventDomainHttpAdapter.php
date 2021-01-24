<?php

declare(strict_types=1);

namespace App\EventDomain\AdminApi\CreateEventDomain;

use App\Event\Model\Event;
use App\Event\Model\EventId;
use App\Event\Model\Events;
use App\EventDomain\Model\EventDomain;
use App\Infrastructure\Http\AppController\AppController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CreateEventDomainHttpAdapter extends AppController
{
    private $events;

    private $em;

    public function __construct(Events $events, EntityManagerInterface $em)
    {
        $this->events = $events;
        $this->em     = $em;
    }

    /**
     * @Route("/admin/eventDomain", methods={"POST"})
     */
    public function __invoke(CreateEventDomainRequest $request): Response
    {
        // TODO wrap transactions on kernel events ?
        $eventId = EventId::new();

        $event = new Event($eventId);
        $this->events->add($event);

        $this->em->persist(
            new EventDomain(
                (string)$eventId,
                $request->name,
                $request->domain
            )
        );

        $this->em->flush();

        return $this->response($eventId);
    }
}