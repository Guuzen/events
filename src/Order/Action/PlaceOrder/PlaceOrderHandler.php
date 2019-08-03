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

    public function handle(PlaceOrder $placeOrder): array
    {
        $orderDate = new DateTimeImmutable();

        $eventId = EventId::fromString($placeOrder->eventId);
        $event   = $this->events->findById($eventId);
        if (null === $event) {
            // TODO нулл плохая замена воиду?
            return [null, 'event not found'];
        }

        $tariffId = TicketTariffId::fromString($placeOrder->tariffId);
        $tariff   = $this->ticketTariffs->findById($tariffId);
        if (null === $tariff) {
            return [null, 'tariff not found'];
        }

        $product = $tariff->findNotReservedProduct($this->products);
        if (null === $product) {
            return [null, 'not reserved product not found'];
        }

        $user = new User(
            UserId::new(),
            new FullName($placeOrder->firstName, $placeOrder->lastName),
            new Contacts($placeOrder->email, $placeOrder->phone)
        );

        // TODO не очень понятно где создавать промокод
        $promocode = new NullPromocode();
        $sum       = $tariff->calculateSum($promocode, $orderDate);
        if (null === $sum) {
            return [null, 'cant calculate sum'];
        }

        $orderId = OrderId::new();
        $order   = $event->makeOrder(
            $orderId,
            $product,
            $tariff,
            $sum,
            $user,
            $orderDate
        );

        $promocode->use($orderId, $tariff, $orderDate);
        $order->applyPromocode($promocode);

        $this->em->persist($user);
        $this->em->persist($order);
        $this->em->flush();

        return [null, null];
    }
}
