<?php

namespace App\Model\Tariff;

use App\Model\Event\EventId;
use App\Model\Tariff\Exception\TariffLoadFailed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class Tariffs extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tariff::class);
    }

    public function findById(TariffId $tariffId, EventId $eventId): Tariff
    {
        $query = $this->createQueryBuilder('tariff')
            ->where('tariff.id = :tariff_id')
            ->andWhere('tariff.eventId = :event_id')
            ->getQuery();
        $query->setParameter('tariff_id', $tariffId);
        $query->setParameter('event_id', $eventId);

        try {
            /** @var Tariff $tariff */
            $tariff = $query->getSingleResult();
        } catch (\Throwable $exception) {
            throw new TariffLoadFailed('', 0, $exception);
        }

        return $tariff;
    }

    public function add(Tariff $tariff): void
    {
        $this->_em->persist($tariff);
    }
}
