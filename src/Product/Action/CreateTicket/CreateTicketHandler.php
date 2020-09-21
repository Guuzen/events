<?php

declare(strict_types=1);

namespace App\Product\Action\CreateTicket;

use App\Event\Model\Events;
use App\Product\Model\TicketId;
use App\Product\Model\Tickets;
use function random_int;

final class CreateTicketHandler
{
    private $events;

    private $tickets;

    public function __construct(Events $events, Tickets $tickets)
    {
        $this->events  = $events;
        $this->tickets = $tickets;
    }

    public function createTicket(CreateTicket $createTicket): TicketId
    {
        $event = $this->events->findById($createTicket->eventId);

        $ticketId = TicketId::new();
        $ticket   = $event->createTicket(
            $ticketId,
            $createTicket->orderId,
            (string)random_int(10000000, 99999999),
            $createTicket->asOf
        );
        $this->tickets->add($ticket);

        return $ticketId;
    }
}
