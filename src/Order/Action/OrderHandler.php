<?php

namespace App\Order\Action;

use App\Common\Error;
use App\Event\Model\Events;
use App\Fondy\Fondy;
use App\Order\Model\OrderId;
use App\Order\Model\Orders;
use App\Promocode\Model\NullPromocode;
use App\Tariff\Model\Tariffs;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class OrderHandler
{
    private $em;

    private $events;

    private $tariffs;

    private $orders;

    private $fondy;

    public function __construct(
        EntityManagerInterface $em,
        Events $events,
        Tariffs $tariffs,
        Orders $orders,
        Fondy $fondy
    )
    {
        $this->em      = $em;
        $this->events  = $events;
        $this->tariffs = $tariffs;
        $this->orders  = $orders;
        $this->fondy   = $fondy;
    }

    /**
     * @return OrderId|Error
     */
    public function placeOrder(PlaceOrder $placeOrder)
    {
        $orderDate = new DateTimeImmutable();

        $event = $this->events->findById($placeOrder->eventId());
        if ($event instanceof Error) {
            return $event;
        }

        $tariffId = $placeOrder->tariffId();
        $tariff   = $this->tariffs->findById($tariffId);
        if ($tariff instanceof Error) {
            return $tariff;
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
            $tariff,
            $placeOrder->userId(),
            $sum,
            $orderDate
        );
        if ($order instanceof Error) {
            return $order;
        }

        // TODO нафиг нужно считать сумму с промокодом, если потом можно всё равно промокод применить ?
        $promocode->use($orderId, $tariff, $orderDate);
        $order->applyPromocode($promocode);

        $this->em->persist($order);

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
