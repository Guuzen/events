<?php

declare(strict_types=1);

namespace App\Model\Ticket\OnOrderEvent;

use App\Model\Event\Events;
use App\Model\Order\OrderMarkedPaid;
use App\Model\Tariff\ProductType;
use App\Model\Ticket\TicketId;
use App\Model\Ticket\Tickets;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

final class OnOrderMarkedPaid implements MessageSubscriberInterface
{
    private $events;

    private $tickets;

    private $em;

    public function __construct(Events $events, Tickets $tickets, EntityManagerInterface $em)
    {
        $this->events  = $events;
        $this->tickets = $tickets;
        $this->em      = $em;
    }

    public static function getHandledMessages(): iterable
    {
        yield OrderMarkedPaid::class => [
            'method' => 'createTicket',
        ];
    }

    // TODO messages should be retried on fails or atleast logged symfony messenger ?
    public function createTicket(OrderMarkedPaid $orderMarkedPaid): void
    {
        if ($orderMarkedPaid->productType->equals(ProductType::ticket()) === false) {
            return; // TODO saga ?
        }

        $event = $this->events->findById($orderMarkedPaid->eventId);

        $ticketId = TicketId::new();
        $ticket   = $event->createTicket(
            $ticketId,
            $orderMarkedPaid->orderId,
            (string)\random_int(10000000, 99999999), // TODO
            new \DateTimeImmutable('now')
        );
        $this->tickets->add($ticket);

        $this->em->flush();
    }
}
