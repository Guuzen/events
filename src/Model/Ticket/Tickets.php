<?php

namespace App\Model\Ticket;

use App\Model\Event\EventId;
use App\Model\Ticket\Exception\TicketLoadFailed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class Tickets extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

    public function findById(TicketId $ticketId, EventId $eventId): Ticket
    {
        $query = $this->createQueryBuilder('ticket')
            ->where('ticket.id = :ticket_id')
            ->andWhere('ticket.eventId = :event_id')
            ->getQuery();
        $query->setParameter('ticket_id', $ticketId);
        $query->setParameter('event_id', $eventId);

        try {
            /** @var Ticket $ticket */
            $ticket = $query->getSingleResult();
        } catch (\Throwable $exception) {
            throw new TicketLoadFailed('', 0, $exception);
        }

        return $ticket;
    }

    public function add(Ticket $ticket): void
    {
        $this->_em->persist($ticket);
    }
}
