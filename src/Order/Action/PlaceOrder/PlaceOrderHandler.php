<?php

declare(strict_types=1);

namespace App\Order\Action\PlaceOrder;

use App\Event\Model\Events;
use App\Order\Model\OrderId;
use App\Order\Model\Orders;
use App\Tariff\Model\Tariffs;

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

        $price = $tariff->calculatePrice($orderDate);

        $orderId = OrderId::new();
        $order   = $event->makeOrder(
            $orderId,
            $tariff,
            $placeOrder->userId,
            $price,
            $orderDate
        );

        $this->orders->add($order);

        return $orderId;
    }
}
