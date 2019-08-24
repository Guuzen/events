<?php

namespace App\Tariff\Model;

use App\Common\Result\Ok;
use App\Common\Result\Result;
use App\Tariff\Model\Error\TariffNotFound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

final class Tariffs extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tariff::class);
    }

    public function findById(TariffId $tariffId): Result
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

        $tariff = $query->getOneOrNullResult();
        if (null === $tariff) {
            return new TariffNotFound();
        }

        return new Ok($tariff);
    }

    public function add(Tariff $tariff): void
    {
        $this->_em->persist($tariff);
    }
}
