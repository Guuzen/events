<?php


namespace App\Event\Model;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

final class Events extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findById(EventId $eventId): ?Event
    {
        $query = $this->_em->createQuery('
            select
                e
            from
                App\Event\Model\Event as e
            where
                e.id = :id
        ');
        $query->setParameter('id', $eventId);

        return $query->getOneOrNullResult();
    }

    public function add(Event $event): void
    {
        $this->_em->persist($event);
    }
}
