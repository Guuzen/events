<?php

namespace App\Order\Model;

use App\Event\Model\EventId;
use App\Order\Model\Exception\LoadOrderFailed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class Orders extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function getById(OrderId $orderId, EventId $eventId): Order
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

        try {
            /** @var Order */
            $order = $query->getSingleResult();
        } catch (\Throwable $exception) {
            throw new LoadOrderFailed('', 0, $exception);
        }

        return $order;
    }

    public function add(Order $order): void
    {
        $this->_em->persist($order);
    }
}
