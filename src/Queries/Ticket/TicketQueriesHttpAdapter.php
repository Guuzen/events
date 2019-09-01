<?php

namespace App\Queries\Ticket;

use App\Common\Error;
use App\Infrastructure\Http\AppController;
use App\Queries\Ticket\FindTicketById\FindTicketById;
use App\Queries\Ticket\FindTicketsInList\FindTicketsInList;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/ticket", methods={"GET"})
 */
final class TicketQueriesHttpAdapter extends AppController
{
    private $ticketQueries;

    public function __construct(TicketQueries $ticketQueries)
    {
        $this->ticketQueries = $ticketQueries;
    }

    /**
     * @Route("/list")
     */
    public function findTicketsInList(FindTicketsInList $findEventTickets): Response
    {
        $tickets = $this->ticketQueries->findTicketsInList($findEventTickets->eventId);

        return $this->successJson($tickets);
    }

    /**
     * @Route("/show")
     */
    public function findById(FindTicketById $findTicketById): Response
    {
        $ticket = $this->ticketQueries->findById($findTicketById->ticketId);
        if ($ticket instanceof Error) {
            return $this->errorJson($ticket);
        }

        return $this->successJson($ticket);
    }
}
