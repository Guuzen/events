<?php

declare(strict_types=1);

namespace App\Product\OnOrderEvent;

use App\Event\Model\Events;
use App\Order\Model\OrderMarkedPaid;
use App\Product\Model\TicketId;
use App\Product\Model\Tickets;
use App\Product\OnOrderEvent\SendTicket\FindTicketEmail\FindTicketEmail;
use App\Product\OnOrderEvent\SendTicket\TicketSending\TicketSending;
use App\Tariff\Model\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class OnOrderMarkedPaid implements EventSubscriberInterface
{
    private $events;

    private $tickets;

    private $logger;

    private $em;

    private $ticketDelivery;

    private $findTicketEmail;

    public function __construct(
        Events $events,
        Tickets $tickets,
        LoggerInterface $logger,
        EntityManagerInterface $em,
        TicketSending $ticketDelivery,
        FindTicketEmail $findTicketEmail
    )
    {
        $this->events          = $events;
        $this->tickets         = $tickets;
        $this->logger          = $logger;
        $this->em              = $em;
        $this->ticketDelivery  = $ticketDelivery;
        $this->findTicketEmail = $findTicketEmail;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OrderMarkedPaid::class => 'createTicket',
        ];
    }

    // TODO messages should be retried on fails or atleast logged symfony messenger ?
    public function createTicket(OrderMarkedPaid $orderMarkedPaid): void
    {
        if ($orderMarkedPaid->productType->equals(ProductType::ticket()) === false) {
            return; // TODO saga ?
        }

        try {
            $event = $this->events->findById($orderMarkedPaid->eventId);

            $ticketId = TicketId::new();
            $ticket   = $event->createTicket(
                $ticketId,
                $orderMarkedPaid->orderId,
                (string)\random_int(10000000, 99999999), // TODO
                new \DateTimeImmutable('now')
            );
            $this->tickets->add($ticket);

        } catch (\Throwable $exception) {
            $this->logger->error(
                'create ticket failed', [
                    'orderId'   => $orderMarkedPaid->orderId,
                    'exception' => $exception,
                ]
            );

            return;
        }

        $this->em->flush();

        // TODO problems with this flow - sagas / bullshit subscribers priorities / change flow ?
        // TODO order marked paid ->
        // TODO 1. create ticket
        // TODO 2. send ticket (application side joins + retries with backoff if ticket still not created)
        try {
            $ticketEmail = $this->findTicketEmail->find($ticketId);

            $this->ticketDelivery->send($ticketEmail);
        } catch (\Throwable $exception) {
            $this->logger->error(
                'send ticket failed', [
                    'orderId'   => $orderMarkedPaid->orderId,
                    'exception' => $exception,
                ]
            );

            return;
        }

        $this->em->flush();
    }
}
