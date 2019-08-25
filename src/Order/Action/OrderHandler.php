<?php

namespace App\Order\Action;

use App\Common\Error;
use App\Event\Model\Error\EventNotFound;
use App\Event\Model\EventId;
use App\Event\Model\Events;
use App\Infrastructure\Notifier\SendTicketToBuyerByEmailNotifier;
use App\Order\Model\Error\OrderAlreadyPaid;
use App\Order\Model\Error\OrderNotFound;
use App\Order\Model\OrderId;
use App\Order\Model\Orders;
use App\Product\Model\Error\NotReservedProductNotFound;
use App\Product\Model\Error\ProductCantBeDeliveredIfNotReserved;
use App\Product\Model\Error\ProductCantBeReservedIfAlreadyReserved;
use App\Product\Model\Error\ProductNotFound;
use App\Product\Model\Products;
use App\Promocode\Model\NullPromocode;
use App\Queries\FindDataForSendTicketToByerByEmail;
use App\Tariff\Model\Error\TariffNotFound;
use App\Tariff\Model\Error\TariffSegmentNotFound;
use App\Tariff\Model\TariffId;
use App\Tariff\Model\Tariffs;
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

    private $tariffs;

    private $products;

    private $orders;

    private $sendTicketToBuyerByEmailNotifier;

    private $findDataForSendTicketToByerByEmail;

    public function __construct(
        EntityManagerInterface $em,
        Events $events,
        Tariffs $tariffs,
        Products $products,
        Orders $orders,
        SendTicketToBuyerByEmailNotifier $sendTicketToBuyerByEmailNotifier,
        FindDataForSendTicketToByerByEmail $findDataForSendTicketToByerByEmail
    ) {
        $this->em                                 = $em;
        $this->events                             = $events;
        $this->tariffs                            = $tariffs;
        $this->products                           = $products;
        $this->orders                             = $orders;
        $this->sendTicketToBuyerByEmailNotifier   = $sendTicketToBuyerByEmailNotifier;
        $this->findDataForSendTicketToByerByEmail = $findDataForSendTicketToByerByEmail;
    }

    /**
     * @return OrderId|EventNotFound|TariffNotFound|NotReservedProductNotFound|TariffSegmentNotFound|ProductNotFound|ProductCantBeReservedIfAlreadyReserved
     */
    public function placeOrder(PlaceOrder $placeOrder)
    {
        $orderDate = new DateTimeImmutable();

        $eventId = EventId::fromString($placeOrder->eventId);
        $event   = $this->events->findById($eventId);
        if ($event instanceof Error) {
            return $event;
        }

        $tariffId = TariffId::fromString($placeOrder->tariffId);
        $tariff   = $this->tariffs->findById($tariffId);
        if ($tariff instanceof Error) {
            return $tariff;
        }

        $product = $tariff->findNotReservedProduct($this->products);
        if ($product instanceof Error) {
            return $product;
        }

        $user = new User(
            UserId::new(),
            new FullName($placeOrder->firstName, $placeOrder->lastName),
            new Contacts($placeOrder->email, $placeOrder->phone)
        );

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

        // TODO explicit add ?
        $this->em->persist($user);
        $this->em->persist($order);
        $this->em->flush();

        return $orderId;
    }

    /**
     * @return OrderNotFound|OrderAlreadyPaid|ProductNotFound|ProductCantBeDeliveredIfNotReserved|null
     */
    public function markOrderPaid(MarkOrderPaid $markOrderPaid)
    {
        $orderId = OrderId::fromString($markOrderPaid->orderId);

        $order = $this->orders->findById($orderId);
        if ($order instanceof Error) {
            return $order;
        }

        $markPaidError = $order->markPaid();
        if ($markPaidError instanceof Error) {
            return $markPaidError;
        }

        $this->em->flush();

        $orderPaid = ($this->findDataForSendTicketToByerByEmail)((string) $orderId);
        $this->sendTicketToBuyerByEmailNotifier->notify($orderPaid);

        $product = $order->findProductById($this->products);
        if ($product instanceof Error) {
            return $product;
        }

        $deliveredError = $product->delivered(new DateTimeImmutable('now'));
        if ($deliveredError instanceof Error) {
            return $deliveredError;
        }

        $this->em->flush();

        return null;
    }
}
