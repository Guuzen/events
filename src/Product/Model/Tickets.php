<?php
declare(strict_types=1);

namespace App\Product\Model;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

final class Tickets extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findById(TicketId $ticketId): ?Product
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

        return $query->getOneOrNullResult();
    }

    public function add(Product $ticket): void
    {
        $this->_em->persist($ticket);
    }
}
