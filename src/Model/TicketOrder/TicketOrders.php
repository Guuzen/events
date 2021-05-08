<?php

declare(strict_types=1);

namespace App\Model\TicketOrder;

use App\Model\Event\EventId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

final class TicketOrders extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TicketOrder::class);
    }

    public function getById(TicketOrderId $ticketOrderId, EventId $eventId): TicketOrder
    {
        $query = $this->createQueryBuilder('ticket_order')
            ->where('ticket_order.id = :ticket_order_id')
            ->andWhere('ticket_order.eventId = :event_id')
            ->setParameter('ticket_order_id', $ticketOrderId)
            ->setParameter('event_id', $eventId)
            ->getQuery();

        try {
            /** @var TicketOrder $ticketOrder */
            $ticketOrder = $query->getSingleResult();
        } catch (NoResultException $exception) {
            throw new TicketOrderNotFound('', 0, $exception);
        }

        return $ticketOrder;
    }

    public function add(TicketOrder $ticketOrder): void
    {
        $this->_em->persist($ticketOrder);
    }
}
