<?php

declare(strict_types=1);

namespace App\EventDomain;

use App\EventDomain\Queries\FindEventById\FindEventByIdQuery;
use App\EventDomain\Queries\FindEventById\FindEventByIdRequest;
use App\EventDomain\Queries\GetEventInList\GetEventListQuery;
use App\Infrastructure\Http\AppController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Simplification because EventDomain is CRUD-ish
 */
final class EventDomainHttpAdapter extends AppController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/admin/eventDomain/create")
     */
    public function create(EventDomain $eventDomain): Response
    {
        $this->em->persist($eventDomain);
        $this->em->flush();

        return $this->response([]);
    }

    /**
     * @Route("/admin/eventDomain/show")
     */
    public function findEventById(FindEventByIdQuery $query, FindEventByIdRequest $request): Response
    {
        $event = $query($request->eventId);

        return $this->response($event);
    }

    /**
     * @Route("/admin/eventDomain/list")
     */
    public function getEventList(GetEventListQuery $query): Response
    {
        $events = $query();

        return $this->response($events);
    }
}
