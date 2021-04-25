<?php

namespace App\Model\Order;

use App\Model\Event\EventId;
use App\Model\Order\Exception\LoadOrderFailed;
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
        $query = $this->createQueryBuilder('ord')
            ->where('ord.id = :order_id')
            ->andWhere('ord.eventId = :event_id')
            ->getQuery();
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
