<?php

namespace App\Promocode\Model;

use App\Event\Model\EventId;
use App\Promocode\Model\Exception\PromocodeNotFound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

final class Promocodes extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promocode::class);
    }

    public function findById(PromocodeId $promocodeId, EventId $eventId): Promocode
    {
        $query = $this->_em->createQuery('
            select
                promocode
            from
                App\Promocode\Model\Promocode as promocode
            where
                promocode.id = :promocode_id
                and
                promocode.eventId = :event_id
        ');
        $query->setParameter('promocode_id', $promocodeId);
        $query->setParameter('event_id', $eventId);

        try {
            /** @var Promocode $promocode */
            $promocode = $query->getOneOrNullResult();
        } catch (\Throwable $exception) {
            throw new PromocodeNotFound('', 0, $exception);
        }

        return $promocode;
    }

    public function findByCode(string $code, EventId $eventId): Promocode
    {
        $query = $this->_em->createQuery('
            select
                promocode
            from
                App\Promocode\Model\Promocode as promocode
            where
                promocode.code = :code
                and
                promocode.eventId = :event_id
        ');
        $query->setParameter('code', $code);
        $query->setParameter('event_id', $eventId);

        try {
            /** @var Promocode $promocode */
            $promocode = $query->getSingleResult();
        } catch (\Throwable $exception) {
            throw new PromocodeNotFound('', 0, $exception);
        }

        return $promocode;
    }

    public function add(Promocode $promocode): void
    {
        $this->_em->persist($promocode);
    }
}
