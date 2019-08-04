<?php

namespace App\Product\Action;

use App\Event\Model\EventId;
use App\Event\Model\Events;
use App\Product\Model\ProductId;
use App\Product\Model\Products;
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
    ) {
        $this->em       = $em;
        $this->products = $products;
        $this->tariffs  = $tariffs;
        $this->tickets  = $tickets;
        $this->events   = $events;
    }

    public function createTicket(CreateTicket $createTicket): array
    {
        $eventId   = EventId::fromString($createTicket->eventId);
        $tariffId  = TariffId::fromString($createTicket->tariffId);
        $productId = ProductId::new();

        $tariff    = $this->tariffs->findById($tariffId);
        if (null === $tariff) {
            return [null, 'tariff not found'];
        }
        $product = $tariff->createProduct($productId, new DateTimeImmutable());
        $this->products->add($product);

        $event  = $this->events->findById($eventId);
        if (null === $event) {
            return [null, 'event not found'];
        }
        $ticket = $event->createTicket(TicketId::fromString($productId), $createTicket->number);
        $this->tickets->add($ticket);

        $this->em->flush();

        return [$productId, null];
    }
}
