<?php

namespace App\Promocode\Model;

use App\Promocode\Model\Error\PromocodeNotFound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

final class RegularPromocodes extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegularPromocode::class);
    }

    /**
     * @return Promocode|PromocodeNotFound
     */
    public function findById(PromocodeId $promocodeId)
    {
        $query = $this->_em->createQuery('
            select
                promocode
            from
                App\Promocode\Model\RegularPromocode as promocode
            where
                promocode.id = :promocode_id
        ');
        $query->setParameter('promocode_id', $promocodeId);

        /** @var Promocode|null */
        $promocode = $query->getOneOrNullResult();
        if (null === $promocode) {
            return new PromocodeNotFound();
        }

        return $promocode;
    }
}
