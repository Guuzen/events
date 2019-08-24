<?php

namespace App\Product\Action;

use App\Common\Result\Ok;
use App\Common\Result\Result;
use App\Event\Model\Event;
use App\Event\Model\EventId;
use App\Event\Model\Events;
use App\Product\Model\ProductId;
use App\Product\Model\Products;
use App\Product\Model\TicketId;
use App\Product\Model\Tickets;
use App\Tariff\Model\Tariff;
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

    public function createTicket(CreateTicket $createTicket): Result
    {
        $eventId   = EventId::fromString($createTicket->eventId);
        $tariffId  = TariffId::fromString($createTicket->tariffId);
        $productId = ProductId::new();

        $findTariffResult = $this->tariffs->findById($tariffId);
        if ($findTariffResult->isErr()) {
            return $findTariffResult;
        }
        /** @var Tariff $tariff */
        $tariff = $findTariffResult->value();

        $product = $tariff->createProduct($productId, new DateTimeImmutable());
        $this->products->add($product);

        $findEventResult = $this->events->findById($eventId);
        if ($findEventResult->isErr()) {
            return $findEventResult;
        }
        /** @var Event $event */
        $event = $findEventResult->value();

        $ticket = $event->createTicket(TicketId::fromString($productId), $createTicket->number);
        $this->tickets->add($ticket);

        $this->em->flush();

        return new Ok($productId);
    }
}
