<?php

namespace App\Product\Model;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

final class Tickets extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

    public function findById(TicketId $ticketId): ?Ticket
    {
        $query = $this->_em->createQuery('
            select
                e
            from
                App\Event\Model\Ticket as e
            where
                e.id = :id
        ');
        $query->setParameter('id', $ticketId);

        /** @var Ticket|null */
        return $query->getOneOrNullResult();
    }

    public function add(Ticket $ticket): void
    {
        $this->_em->persist($ticket);
    }
}
