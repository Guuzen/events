<?php

namespace App\Order\Action;

use App\Event\Model\EventId;
use App\Event\Model\Events;
use App\Order\Model\OrderId;
use App\Order\Model\Orders;
use App\Product\Model\Products;
use App\Promocode\Model\NullPromocode;
use App\Tariff\Model\TariffId;
use App\Tariff\Model\TicketTariffs;
use App\User\Model\Contacts;
use App\User\Model\FullName;
use App\User\Model\User;
use App\User\Model\UserId;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

final class OrderHandler
{
    private $em;

    private $events;

    private $ticketTariffs;

    private $products;
    private $orders;

    public function __construct(
        EntityManagerInterface $em,
        Events $events,
        TicketTariffs $ticketTariffs,
        Products $products,
        Orders $orders
    ) {
        $this->em = $em;
        $this->events = $events;
        $this->ticketTariffs = $ticketTariffs;
        $this->products = $products;
        $this->orders = $orders;
    }

    public function placeOrder(PlaceOrder $placeOrder): array
    {
        $orderDate = new DateTimeImmutable();

        $eventId = EventId::fromString($placeOrder->eventId);
        $event   = $this->events->findById($eventId);
        if (null === $event) {
            // TODO нулл плохая замена воиду?
            return [null, 'event not found'];
        }

        $tariffId = TariffId::fromString($placeOrder->tariffId);
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

        // TODO нафиг нужно считать сумму с промокодом, если потом можно всё равно промокод применить ?
        $promocode->use($orderId, $tariff, $orderDate);
        $order->applyPromocode($promocode);

        $this->em->persist($user);
        $this->em->persist($order);
        $this->em->flush();

        return [$orderId, null];
    }

    public function markOrderPaid(MarkOrderPaid $markOrderPaid): array
    {
        $orderId = OrderId::fromString($markOrderPaid->orderId);

        $order = $this->orders->findById($orderId);
        if (null === $order) {
            return [null, 'order not found'];
        }

        $order->markPaid();

        $this->em->flush();

        return [null, null];
    }
}
