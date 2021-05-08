<?php

declare(strict_types=1);

namespace App\Model\Ticket\OnOrderEvent;

use App\Model\Ticket\Ticket;
use App\Model\Ticket\TicketId;
use App\Model\Ticket\Tickets;
use App\Model\TicketOrder\TicketOrderPaymentConfirmed;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

final class OnTicketOrderPaymentConfirmed implements MessageSubscriberInterface
{
    private $tickets;

    private $em;

    public function __construct(Tickets $tickets, EntityManagerInterface $em)
    {
        $this->tickets = $tickets;
        $this->em      = $em;
    }

    public static function getHandledMessages(): iterable
    {
        yield TicketOrderPaymentConfirmed::class => [
            'method' => 'createTicket',
        ];
    }

    public function createTicket(TicketOrderPaymentConfirmed $event): void
    {
        $ticketId = TicketId::new();
        $ticket   = new Ticket(
            $ticketId,
            $event->eventId,
            $event->orderId,
            $event->userId,
            (string)\random_int(10000000, 99999999),
            new \DateTimeImmutable('now'),
        );

        $this->tickets->add($ticket);

        $this->em->flush();
    }
}
