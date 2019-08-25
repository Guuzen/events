<?php

namespace App\Queries\Ticket;

use App\Common\AppController;
use Symfony\Component\HttpFoundation\Request;
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
    public function findAll(Request $request): Response
    {
        $tickets = $this->ticketQueries->findAll((string) $request->get('event_id'));

        return $this->successJson($tickets);
    }

    /**
     * @Route("/show")
     */
    public function findById(Request $request): Response
    {
        $ticketId = (string) $request->get('ticket_id');
        $ticket   = $this->ticketQueries->findById($ticketId);

        return $this->successJson($ticket);
    }
}
