<?php

namespace App\Order\Action;

use App\Common\Error;
use App\Event\Model\EventId;
use App\Event\Model\Events;
use App\Fondy\Fondy;
use App\Order\Model\OrderId;
use App\Order\Model\Orders;
use App\Product\Model\Products;
use App\Promocode\Model\NullPromocode;
use App\Tariff\Model\TariffId;
use App\Tariff\Model\Tariffs;
use App\User\Model\UserId;
use App\User\Model\Users;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class OrderHandler
{
    private $em;

    private $events;

    private $tariffs;

    private $products;

    private $orders;

    private $fondy;

    private $users;

    public function __construct(
        EntityManagerInterface $em,
        Events $events,
        Tariffs $tariffs,
        Products $products,
        Orders $orders,
        Fondy $fondy,
        Users $users
    )
    {
        $this->em       = $em;
        $this->events   = $events;
        $this->tariffs  = $tariffs;
        $this->products = $products;
        $this->orders   = $orders;
        $this->fondy    = $fondy;
        $this->users    = $users;
    }

    /**
     * @return OrderId|Error
     */
    public function placeOrder(PlaceOrder $placeOrder)
    {
        $orderDate = new DateTimeImmutable();

        $eventId = new EventId($placeOrder->eventId);
        $event   = $this->events->findById($eventId);
        if ($event instanceof Error) {
            return $event;
        }

        $tariffId = new TariffId($placeOrder->tariffId);
        $tariff   = $this->tariffs->findById($tariffId);
        if ($tariff instanceof Error) {
            return $tariff;
        }

        $product = $this->products->findNotReservedByTariffId($tariffId);
        if ($product instanceof Error) {
            return $product;
        }

        $user = $this->users->findById(new UserId($placeOrder->userId));
        if ($user instanceof Error) {
            return $user;
        }

        // TODO не очень понятно где создавать промокод
        $promocode = new NullPromocode();
        $sum       = $tariff->calculateSum($promocode, $orderDate);
        if ($sum instanceof Error) {
            return $sum;
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
        if ($order instanceof Error) {
            return $order;
        }

        // TODO нафиг нужно считать сумму с промокодом, если потом можно всё равно промокод применить ?
        $promocode->use($orderId, $tariff, $orderDate);
        $order->applyPromocode($promocode);

        $product = $order->findProductById($this->products);
        if ($product instanceof Error) {
            return $product;
        }

        $reservedError = $product->reserve();
        if ($reservedError instanceof Error) {
            return $reservedError;
        }

        $this->em->persist($order);
        $this->em->flush();

        return $orderId;
    }

    public function markOrderPaid(MarkOrderPaid $markOrderPaid): ?Error
    {
        $orderId = new OrderId($markOrderPaid->orderId);

        $order = $this->orders->findById($orderId);
        if ($order instanceof Error) {
            return $order;
        }

        $markPaidError = $order->markPaid();
        if ($markPaidError instanceof Error) {
            return $markPaidError;
        }

        $this->em->flush();

        return null;
    }

    /**
     * @return string|Error
     */
    public function payByCard(PayByCard $payByCard)
    {
        $orderId = new OrderId($payByCard->orderId);

        $order = $this->orders->findById($orderId);
        if ($order instanceof Error) {
            return $order;
        }

        return $order->createFondyPayment($this->fondy);
    }

    public function markOrderPaidByFondy(MarkOrderPaidByFondy $markOrderPaidByFondy): ?Error
    {
        $orderId = new OrderId($markOrderPaidByFondy->orderId);

        $order = $this->orders->findById($orderId);
        if ($order instanceof Error) {
            return $order;
        }

        $markPaidError = $order->markPaid();
        if ($markPaidError instanceof Error) {
            return $markPaidError;
        }

        $this->em->flush();

        return null;
    }
}
