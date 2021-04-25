<?php

namespace App\Model\Event;

use App\Model\Event\Exception\LoadEventFailed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class Events extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findById(EventId $eventId): Event
    {
        $query = $this->_em->createQueryBuilder()
            ->select('e')
            ->from(Event::class, 'e')
            ->where('e.id = :id')
            ->setParameter('id', $eventId)
            ->getQuery();

        try {
            /** @var Event */
            $event = $query->getSingleResult();
        } catch (\Throwable $exception) {
            throw new LoadEventFailed('', 0, $exception);
        }

        return $event;
    }

    public function add(Event $event): void
    {
        $this->_em->persist($event);
    }
}
