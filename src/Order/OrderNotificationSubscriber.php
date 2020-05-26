<?php

namespace App\Order;

use App\Common\Error;
use App\Order\Model\OrderMarkedPaid;
use App\Product\Action\CreateTicket\CreateTicket;
use App\Product\Action\CreateTicket\CreateTicketHandler;
use App\Product\Action\SendTicket\SendTicket;
use App\Product\Action\SendTicket\SendTicketHandler;
use App\Product\Model\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class OrderNotificationSubscriber implements EventSubscriberInterface
{
    private $createTicketHandler;

    private $sendTicketHandler;

    private $logger;

    private $em;

    public function __construct(
        CreateTicketHandler $createTicketHandler,
        SendTicketHandler $sendTicketHandler,
        EntityManagerInterface $em,
        LoggerInterface $logger
    )
    {
        $this->createTicketHandler = $createTicketHandler;
        $this->sendTicketHandler   = $sendTicketHandler;
        $this->em                  = $em;
        $this->logger              = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OrderMarkedPaid::class => 'onOrderMarkedPaid',
        ];
    }

    public function onOrderMarkedPaid(OrderMarkedPaid $orderMarkedPaid): void
    {
        if ($orderMarkedPaid->productType->equals(ProductType::ticket()) === false) {
            return; // TODO saga ?
        }

        $ticketId = $this->createTicketHandler->createTicket(
            new CreateTicket($orderMarkedPaid->eventId, $orderMarkedPaid->orderId, new \DateTimeImmutable('now'))
        );

        // TODO move to exceptions
        if ($ticketId instanceof Error) {
            $this->logger->error('create ticket failed', [
                'orderId' => $orderMarkedPaid->orderId,
                'error'   => \get_class($ticketId),
            ]);

            return;
        }

        $this->em->flush();


        $error = $this->sendTicketHandler->handle(new SendTicket($ticketId));

        if ($error instanceof Error) {
            $this->logger->error('send ticket failed', [
                'orderId' => $orderMarkedPaid->orderId,
                'error'   => \get_class($error),
            ]);
        }

        $this->em->flush();
    }
}
