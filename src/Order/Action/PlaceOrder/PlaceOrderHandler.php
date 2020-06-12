<?php

declare(strict_types=1);

namespace App\Order\Action\PlaceOrder;

use App\Common\Error;
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

    /**
     * @return OrderId|Error
     */
    public function handle(PlaceOrder $placeOrder)
    {
        $orderDate = new \DateTimeImmutable();

        $event = $this->events->findById($placeOrder->eventId);
        if ($event instanceof Error) {
            return $event;
        }

        $tariff = $this->tariffs->findById($placeOrder->tariffId, $placeOrder->eventId);
        if ($tariff instanceof Error) {
            return $tariff;
        }

        $discount = new FixedDiscount(new Money(0, new Currency('RUB')));
        $sum      = $tariff->calculateSum($discount, $orderDate); // TODO doouble dispatch
        if ($sum instanceof Error) {
            return $sum;
        }

        $orderId = OrderId::new();
        $order   = $event->makeOrder(
            $orderId,
            $tariff,
            $placeOrder->userId,
            $sum,
            $orderDate
        );
        if ($order instanceof Error) {
            return $order;
        }

        $this->orders->add($order);

        return $orderId;
    }

}
