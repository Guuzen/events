<?php

declare(strict_types=1);

namespace App\Order\Action\PlaceOrder;

use App\Event\Model\Events;
use App\Order\Model\OrderId;
use App\Order\Model\Orders;
use App\Promocode\Model\Discount\FixedDiscount;
use App\Tariff\Model\Tariffs;
use Money\Currency;
use Money\Money;

final class PlaceOrderHandler
{
    private $events;

    private $orders;

    private $tariffs;

    public function __construct(Events $events, Orders $orders, Tariffs $tariffs)
    {
        $this->events  = $events;
        $this->orders  = $orders;
        $this->tariffs = $tariffs;
    }

    public function handle(PlaceOrder $placeOrder): OrderId
    {
        $orderDate = new \DateTimeImmutable();

        $event = $this->events->findById($placeOrder->eventId);

        $tariff = $this->tariffs->findById($placeOrder->tariffId, $placeOrder->eventId);

        $discount = new FixedDiscount(new Money(0, new Currency('RUB')));
        $sum      = $tariff->calculateSum($discount, $orderDate); // TODO doouble dispatch

        $orderId = OrderId::new();
        $order   = $event->makeOrder(
            $orderId,
            $tariff,
            $placeOrder->userId,
            $sum,
            $orderDate
        );

        $this->orders->add($order);

        return $orderId;
    }
}
