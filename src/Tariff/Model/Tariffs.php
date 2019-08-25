<?php

namespace App\Tariff\Model;

use App\Common\Error;
use App\Tariff\Model\Error\TariffNotFound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

final class Tariffs extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tariff::class);
    }

    /**
     * @return Tariff|Error
     */
    public function findById(TariffId $tariffId)
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

        /** @var Tariff|null */
        $tariff = $query->getOneOrNullResult();
        if (null === $tariff) {
            return new TariffNotFound();
        }

        return $tariff;
    }

    public function add(Tariff $tariff): void
    {
        $this->_em->persist($tariff);
    }
}
