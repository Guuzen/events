<?php

namespace App\Order\Action\PlaceOrder;

use App\Event\Model\EventId;
use App\Event\Model\Events;
use App\Order\Model\OrderId;
use App\Product\Model\Products;
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

    private $products;

    public function __construct(
        EntityManagerInterface $em,
        Events $events,
        TicketTariffs $ticketTariffs,
        Products $products
    ) {
        $this->em            = $em;
        $this->events        = $events;
        $this->ticketTariffs = $ticketTariffs;
        $this->products      = $products;
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

        $product = $ticketTariff->findNotReservedProduct($this->products);
        if (null === $product) {
            return [null, 'not reserved product not found'];
        }

        $user = new User(
            UserId::new(),
            new FullName($orderTicketByWire->firstName, $orderTicketByWire->lastName),
            new Contacts($orderTicketByWire->email, $orderTicketByWire->phone)
        );

        $orderDate = new DateTimeImmutable();
        $orderId   = OrderId::new();
        $order     = $event->makeOrder(
            $orderId,
            $product,
            $ticketTariff,
            new NullPromocode(),
            $user,
            $orderDate
        );

        $this->em->persist($product);
        $this->em->persist($user);
        $this->em->persist($order);
        $this->em->flush();

        return [null, null];
    }
}
