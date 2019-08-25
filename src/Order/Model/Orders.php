<?php

namespace App\Order\Model;

use App\Common\Error;
use App\Order\Model\Error\OrderNotFound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

final class Orders extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * @return Order|Error
     */
    public function findById(OrderId $orderId)
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

        /** @var Order|null */
        $order = $query->getOneOrNullResult();
        if (null === $order) {
            return new OrderNotFound();
        }

        return $order;
    }
}
