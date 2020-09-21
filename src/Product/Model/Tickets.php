<?php

namespace App\Product\Model;

use App\Event\Model\EventId;
use App\Product\Model\Exception\TicketNotFound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

final class Tickets extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

    public function findById(TicketId $ticketId, EventId $eventId): Ticket
    {
        $query = $this->_em->createQuery('
            select
                ticket
            from
                App\Product\Model\Ticket as ticket
            where
                ticket.id = :ticket_id
                and
                ticket.eventId = :event_id
        ');
        $query->setParameter('ticket_id', $ticketId);
        $query->setParameter('event_id', $eventId);

        try {
            /** @var Ticket $ticket */
            $ticket = $query->getSingleResult();
        } catch (\Throwable $exception) {
            throw new TicketNotFound('', 0, $exception);
        }

        return $ticket;
    }

    public function add(Ticket $ticket): void
    {
        $this->_em->persist($ticket);
    }
}
