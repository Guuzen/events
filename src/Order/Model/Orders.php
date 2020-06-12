<?php

namespace App\Order\Model;

use App\Event\Model\EventId;
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
     * @return Order|OrderNotFound
     */
    public function findById(OrderId $orderId, EventId $eventId)
    {
        $query = $this->_em->createQuery('
            select
                ord
            from
                App\Order\Model\Order as ord
            where
                ord.id = :order_id
                and
                ord.eventId = :event_id
        ');
        $query->setParameter('order_id', $orderId);
        $query->setParameter('event_id', $eventId);

        /** @var Order|null */
        $order = $query->getOneOrNullResult();
        if (null === $order) {
            return new OrderNotFound();
        }

        return $order;
    }

    public function add(Order $order): void
    {
        $this->_em->persist($order);
    }
}
