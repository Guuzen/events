<?php

namespace App\Product\Model;

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
    public function findById(TicketId $ticketId)
    {
        $query = $this->_em->createQuery('
            select
                t
            from
                App\Product\Model\Ticket as t
            where
                t.id = :id
        ');
        $query->setParameter('id', $ticketId);

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
