<?php

namespace App\Order;

use App\Common\Error;
use App\Order\Action\ApplyDiscount\ApplyDiscount;
use App\Order\Action\ApplyDiscount\ApplyDiscountHandler;
use App\Order\Model\OrderMarkedPaid;
use App\Product\Action\CreateTicket\CreateTicket;
use App\Product\Action\CreateTicket\CreateTicketHandler;
use App\Product\Action\SendTicket\SendTicket;
use App\Product\Action\SendTicket\SendTicketHandler;
use App\Promocode\Model\PromocodeUsed;
use App\Tariff\Model\ProductType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use function get_class;

final class OrderSubscriber implements EventSubscriberInterface
{
    private $applyDiscountHandler;

    private $createTicketHandler;

    private $sendTicketHandler;

    private $logger;

    private $em;

    public function __construct(
        ApplyDiscountHandler $applyDiscountHandler,
        CreateTicketHandler $createTicketHandler,
        SendTicketHandler $sendTicketHandler,
        EntityManagerInterface $em,
        LoggerInterface $logger
    )
    {
        $this->applyDiscountHandler = $applyDiscountHandler;
        $this->createTicketHandler  = $createTicketHandler;
        $this->sendTicketHandler    = $sendTicketHandler;
        $this->em                   = $em;
        $this->logger               = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OrderMarkedPaid::class => 'onOrderMarkedPaid',
            PromocodeUsed::class   => 'onPromocodeUsed',
        ];
    }

    public function onPromocodeUsed(PromocodeUsed $event): void
    {
        // TODO try catch + log is not helpful. There is must be user notification about error
        $this->applyDiscountHandler->handle(
            new ApplyDiscount(
                $event->eventId,
                $event->orderId,
                $event->discount
            )
        );

        $this->em->flush();
    }

    public function onOrderMarkedPaid(OrderMarkedPaid $orderMarkedPaid): void
    {
        if ($orderMarkedPaid->productType->equals(ProductType::ticket()) === false) {
            return; // TODO saga ?
        }

        try {
            $ticketId = $this->createTicketHandler->createTicket(
                new CreateTicket($orderMarkedPaid->eventId, $orderMarkedPaid->orderId, new DateTimeImmutable('now'))
            );
        } catch (\Throwable $exception) {
            $this->logger->error('create ticket failed', [
                'orderId'   => $orderMarkedPaid->orderId,
                'exception' => $exception,
            ]);

            return;
        }

        $this->em->flush();


        $error = $this->sendTicketHandler->handle(new SendTicket($ticketId));

        if ($error instanceof Error) {
            $this->logger->error('send ticket failed', [
                'orderId' => $orderMarkedPaid->orderId,
                'error'   => get_class($error),
            ]);
        }

        $this->em->flush();
    }
}
