<?php

namespace App\Product\Model;

use App\Event\Model\EventId;
use App\Product\Model\Error\TicketNotFound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

final class Tickets extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

    /**
     * @return Ticket|TicketNotFound
     */
    public function findById(TicketId $ticketId, EventId $eventId)
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

        /** @var Ticket|null */
        $ticket = $query->getOneOrNullResult();
        if ($ticket === null) {
            return new TicketNotFound();
        }

        return $ticket;
    }

    public function add(Ticket $ticket): void
    {
        $this->_em->persist($ticket);
    }
}
