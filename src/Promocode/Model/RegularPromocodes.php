<?php
declare(strict_types=1);

namespace App\Promocode\Model;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

final class RegularPromocodes extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegularPromocode::class);
    }

    // TODO rename promocodeId to RegularPromocodeId
    public function findById(PromocodeId $promocodeId): ?RegularPromocode
    {
        $query = $this->_em->createQuery('
            select
                promocode
            from App\Promocode\Model\RegularPromocode as promocode
            where promocode.id = :promocode_id
        ');
        $query->setParameter('promocode_id', $promocodeId);

        return $query->getOneOrNullResult();
    }
}
