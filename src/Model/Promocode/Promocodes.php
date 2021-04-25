<?php

namespace App\Model\Promocode;

use App\Model\Event\EventId;
use App\Model\Promocode\Exception\PromocodeLoadFailed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class Promocodes extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promocode::class);
    }

    public function findById(PromocodeId $promocodeId, EventId $eventId): Promocode
    {
        $query = $this->createQueryBuilder('promocode')
            ->where('promocode.id = :promocode_id')
            ->andWhere('promocode.eventId = :event_id')
            ->getQuery();
        $query->setParameter('promocode_id', $promocodeId);
        $query->setParameter('event_id', $eventId);

        try {
            /** @var Promocode $promocode */
            $promocode = $query->getOneOrNullResult();
        } catch (\Throwable $exception) {
            throw new PromocodeLoadFailed('', 0, $exception);
        }

        return $promocode;
    }

    public function findByCode(string $code, EventId $eventId): Promocode
    {
        $query = $this->createQueryBuilder('promocode')
            ->where('promocode.code = :code')
            ->andWhere('promocode.eventId = :event_id')
            ->getQuery();
        $query->setParameter('code', $code);
        $query->setParameter('event_id', $eventId);

        try {
            /** @var Promocode $promocode */
            $promocode = $query->getSingleResult();
        } catch (\Throwable $exception) {
            throw new PromocodeLoadFailed('', 0, $exception);
        }

        return $promocode;
    }

    public function add(Promocode $promocode): void
    {
        $this->_em->persist($promocode);
    }
}
