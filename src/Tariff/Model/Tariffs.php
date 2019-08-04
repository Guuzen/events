<?php

namespace App\Tariff\Model;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

final class Tariffs extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tariff::class);
    }

    public function findById(TariffId $tariffId): ?Tariff
    {
        $query = $this->_em->createQuery('
            select
                tariff
            from
                App\Tariff\Model\Tariff as tariff
            where
                tariff.id = :tariff_id
        ');
        $query->setParameter('tariff_id', $tariffId);

        return $query->getOneOrNullResult();
    }

    public function add(Tariff $tariff): void
    {
        $this->_em->persist($tariff);
    }
}
