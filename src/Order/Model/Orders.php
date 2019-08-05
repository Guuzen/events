<?php


namespace App\Order\Model;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

final class Orders extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function findById(OrderId $orderId): ?Order
    {
        $query = $this->_em->createQuery('
            select
                i
            from
                App\Order\Model\Order as i
            where
                i.id = :order_id
        ');
        $query->setParameter('order_id', $orderId);

        return $query->getOneOrNullResult();
    }
}
