<?php

namespace App\Product\Action;

use App\Common\Error;
use App\Event\Model\EventId;
use App\Event\Model\Events;
use App\Product\Model\ProductId;
use App\Product\Model\Products;
use App\Product\Model\ProductType;
use App\Product\Model\TicketId;
use App\Product\Model\Tickets;
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

    public function __construct(
        EntityManagerInterface $em,
        Products $products,
        Tariffs $tariffs,
        Tickets $tickets,
        Events $events
    )
    {
        $this->em       = $em;
        $this->products = $products;
        $this->tariffs  = $tariffs;
        $this->tickets  = $tickets;
        $this->events   = $events;
    }

    /**
     * @return ProductId|Error
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

        $ticket = $event->createTicket(new TicketId((string)$productId), $createTicket->number);
        $this->tickets->add($ticket);

        $this->em->flush();

        return $productId;
    }

    public function deliverProduct(DeliverProduct $deliverProduct): ?Error
    {
        $productId = new ProductId($deliverProduct->productId);

        $product = $this->products->findById($productId);
        if ($product instanceof Error) {
            return $product;
        }

        $error = $product->deliver(new DateTimeImmutable('now'));
        if ($error instanceof Error) {
            return $error;
        }

        return null;
    }
}
