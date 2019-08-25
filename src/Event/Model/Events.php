<?php

namespace App\Event\Model;

use App\Event\Model\Error\EventNotFound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

final class Events extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * @return Event|EventNotFound
     */
    public function findById(EventId $eventId)
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

        /** @var Event|null */
        $event = $query->getOneOrNullResult();
        if (null === $event) {
            return new EventNotFound();
        }

        return $event;
    }

    public function add(Event $event): void
    {
        $this->_em->persist($event);
    }
}
