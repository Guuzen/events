<?php

namespace App\Order\Action;

use App\Common\Result\Ok;
use App\Common\Result\Result;
use App\Event\Model\Event;
use App\Event\Model\EventId;
use App\Event\Model\Events;
use App\Infrastructure\Notifier\SendTicketToBuyerByEmailNotifier;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Order\Model\Orders;
use App\Product\Model\Product;
use App\Product\Model\Products;
use App\Promocode\Model\NullPromocode;
use App\Queries\FindDataForSendTicketToByerByEmail;
use App\Tariff\Model\Tariff;
use App\Tariff\Model\TariffId;
use App\Tariff\Model\Tariffs;
use App\User\Model\Contacts;
use App\User\Model\FullName;
use App\User\Model\User;
use App\User\Model\UserId;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Money\Money;

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

    public function placeOrder(PlaceOrder $placeOrder): Result
    {
        $orderDate = new DateTimeImmutable();

        $eventId         = EventId::fromString($placeOrder->eventId);
        $findEventResult = $this->events->findById($eventId);
        if ($findEventResult->isErr()) {
            return $findEventResult;
        }
        /** @var Event $event */
        $event = $findEventResult->value();

        $tariffId         = TariffId::fromString($placeOrder->tariffId);
        $findTariffResult = $this->tariffs->findById($tariffId);
        if ($findTariffResult->isErr()) {
            return $findTariffResult;
        }
        /** @var Tariff $tariff */
        $tariff = $findTariffResult->value();

        $findProductResult = $tariff->findNotReservedProduct($this->products);
        if ($findProductResult->isErr()) {
            return $findProductResult;
        }
        /** @var Product $product */
        $product = $findProductResult->value();

        $user = new User(
            UserId::new(),
            new FullName($placeOrder->firstName, $placeOrder->lastName),
            new Contacts($placeOrder->email, $placeOrder->phone)
        );

        // TODO не очень понятно где создавать промокод
        $promocode          = new NullPromocode();
        $calculateSumResult = $tariff->calculateSum($promocode, $orderDate);
        if ($calculateSumResult->isErr()) {
            return $calculateSumResult;
        }
        /** @var Money $sum */
        $sum = $calculateSumResult->value();

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

        $findProductResult = $order->findProductById($this->products);
        if ($findProductResult->isErr()) {
            return $findProductResult;
        }
        /** @var Product $product */
        $product = $findProductResult->value();

        $reserveResult = $product->reserve();
        if ($reserveResult->isErr()) {
            return $reserveResult;
        }

        // TODO explicit add ?
        $this->em->persist($user);
        $this->em->persist($order);
        $this->em->flush();

        return new Ok($orderId);
    }

    public function markOrderPaid(MarkOrderPaid $markOrderPaid): Result
    {
        $orderId = OrderId::fromString($markOrderPaid->orderId);

        $findOrderResult = $this->orders->findById($orderId);
        if ($findOrderResult->isErr()) {
            return $findOrderResult;
        }
        /** @var Order $order */
        $order = $findOrderResult->value();

        $markPaidResult = $order->markPaid();
        if ($markPaidResult->isErr()) {
            return $markPaidResult;
        }

        $this->em->flush();

        $orderPaid = ($this->findDataForSendTicketToByerByEmail)($orderId);
        $this->sendTicketToBuyerByEmailNotifier->notify($orderPaid);

        $findProductResult = $order->findProductById($this->products);
        if ($findProductResult->isErr()) {
            return $findProductResult;
        }
        /** @var Product $product */
        $product = $findProductResult->value();

        $deliveredResult = $product->delivered(new DateTimeImmutable('now'));
        if ($deliveredResult->isErr()) {
            return $deliveredResult;
        }

        $this->em->flush();

        return new Ok();
    }
}
