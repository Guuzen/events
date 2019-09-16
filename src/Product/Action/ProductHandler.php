<?php

namespace App\Product\Action;

use App\Common\Error;
use App\Event\Model\Error\EventNotFound;
use App\Event\Model\EventId;
use App\Event\Model\Events;
use App\Product\Model\Error\ProductCantBeDeliveredIfNotReserved;
use App\Product\Model\Error\ProductNotFound;
use App\Product\Model\ProductId;
use App\Product\Model\Products;
use App\Product\Model\ProductType;
use App\Product\Model\TicketId;
use App\Product\Model\Tickets;
use App\Product\Service\Error\ProductEmailNotFound;
use App\Product\Service\ProductEmailDelivery;
use App\Product\Service\Error\ProductNotDelivered;
use App\Tariff\Model\Error\TariffNotFound;
use App\Tariff\Model\TariffId;
use App\Tariff\Model\Tariffs;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

final class ProductHandler
{
    private $em;

    private $products;

    private $tariffs;

    private $tickets;

    private $events;

    private $productEmailDelivery;

    public function __construct(
        EntityManagerInterface $em,
        Products $products,
        Tariffs $tariffs,
        Tickets $tickets,
        Events $events,
        ProductEmailDelivery $productEmailDelivery
    ) {
        $this->em                   = $em;
        $this->products             = $products;
        $this->tariffs              = $tariffs;
        $this->tickets              = $tickets;
        $this->events               = $events;
        $this->productEmailDelivery = $productEmailDelivery;
    }

    /**
     * @return ProductId|TariffNotFound|EventNotFound
     */
    public function createTicket(CreateTicket $createTicket)
    {
        $eventId   = new EventId($createTicket->eventId);
        $tariffId  = new TariffId($createTicket->tariffId);
        $productId = ProductId::new();

        $tariff = $this->tariffs->findById($tariffId);
        if ($tariff instanceof Error) {
            return $tariff;
        }

        $product = $tariff->createProduct($productId, new DateTimeImmutable(), ProductType::ticket());
        $this->products->add($product);

        $event = $this->events->findById($eventId);
        if ($event instanceof Error) {
            return $event;
        }

        $ticket = $event->createTicket(new TicketId((string) $productId), $createTicket->number);
        $this->tickets->add($ticket);

        $this->em->flush();

        return $productId;
    }

    /**
     * @return ProductNotFound|ProductCantBeDeliveredIfNotReserved|ProductNotDelivered|ProductEmailNotFound|null
     */
    public function deliverProduct(DeliverProduct $deliverProduct)
    {
        $productId = new ProductId($deliverProduct->productId);

        $product = $this->products->findById($productId);
        if ($product instanceof Error) {
            return $product;
        }

        $error = $product->deliver($this->productEmailDelivery, new DateTimeImmutable('now'));
        if ($error instanceof Error) {
            return $error;
        }

        $this->em->flush();

        return null;
    }
}
