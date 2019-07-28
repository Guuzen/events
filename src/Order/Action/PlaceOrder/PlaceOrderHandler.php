<?php

namespace App\Order\Action\PlaceOrder;

use App\Event\Model\EventId;
use App\Event\Model\Events;
use App\Order\Model\OrderId;
use App\Product\Model\TicketId;
use App\Promocode\Model\NullPromocode;
use App\Tariff\Model\TicketTariffId;
use App\Tariff\Model\TicketTariffs;
use App\User\Model\Contacts;
use App\User\Model\FullName;
use App\User\Model\User;
use App\User\Model\UserId;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

final class PlaceOrderHandler
{
    private $em;

    private $events;

    private $ticketTariffs;

    public function __construct(
        EntityManagerInterface $em,
        Events $events,
        TicketTariffs $ticketTariffs
    ) {
        $this->em            = $em;
        $this->events        = $events;
        $this->ticketTariffs = $ticketTariffs;
    }

    public function handle(PlaceOrder $orderTicketByWire): array
    {
        $eventId = EventId::fromString('ac28bf81-08c6-4fc0-beae-7d4aabf1396e');
        $event   = $this->events->findById($eventId);
        if (null === $event) {
            // TODO нулл плохая замена воиду?
            return [null, 'event not found'];
        }

        $tariffId     = TicketTariffId::fromString($orderTicketByWire->tariffId);
        $ticketTariff = $this->ticketTariffs->findById($tariffId);

        if (null === $ticketTariff) {
            return [null, 'tariff not found'];
        }

        $ticketId = TicketId::new();
        $ticket   = $ticketTariff->createTicket($ticketId, random_int(10 * 1000000, 100 * 1000000 - 1));

        $user = new User(
            UserId::new(),
            new FullName($orderTicketByWire->firstName, $orderTicketByWire->lastName),
            new Contacts($orderTicketByWire->email, $orderTicketByWire->phone)
        );

        $orderDate = new DateTimeImmutable();
        $orderId   = OrderId::new();

        $order = $event->makeOrder(
            $orderId,
            $ticket,
            $ticketTariff,
            new NullPromocode(),
            $user,
            $orderDate
        );

        $this->em->persist($ticket);
        $this->em->persist($user);
        $this->em->persist($order);
        $this->em->flush();

        return [null, null];
    }
}
