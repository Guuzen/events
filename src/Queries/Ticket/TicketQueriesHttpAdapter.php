<?php

namespace App\Queries\Ticket;

use App\Infrastructure\Http\AppController;
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
    public function findEventTickets(FindEventTickets $findEventTickets): Response
    {
        $tickets = $this->ticketQueries->findAll($findEventTickets->eventId);

        return $this->successJson($tickets);
    }

    /**
     * @Route("/show")
     */
    public function findById(FindTicketById $findTicketById): Response
    {
        $ticket = $this->ticketQueries->findById($findTicketById->ticketId);

        return $this->successJson($ticket);
    }
}
