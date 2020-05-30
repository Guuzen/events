<?php

declare(strict_types=1);

namespace App\Product\Action\CreateTicket;

use App\Common\Error;
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

    /**
     * @return TicketId|Error
     */
    public function createTicket(CreateTicket $createTicket)
    {
        $event = $this->events->findById($createTicket->eventId);
        if ($event instanceof Error) {
            return $event;
        }

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
