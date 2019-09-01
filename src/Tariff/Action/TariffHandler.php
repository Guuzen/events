<?php

namespace App\Tariff\Action;

use App\Common\Error;
use App\Event\Model\Error\EventNotFound;
use App\Event\Model\EventId;
use App\Event\Model\Events;
use App\Product\Model\ProductType;
use App\Tariff\Model\TariffId;
use App\Tariff\Model\TariffPriceNet;
use App\Tariff\Model\Tariffs;
use App\Tariff\Model\TariffSegment;
use App\Tariff\Model\TariffTerm;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Money\Currency;
use Money\Money;

final class TariffHandler
{
    private $tariffs;
    /**
     * @var Events
     */
    private $events;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em, Tariffs $tariffs, Events $events)
    {
        $this->em      = $em;
        $this->tariffs = $tariffs;
        $this->events  = $events;
    }

    /**
     * @return TariffId|EventNotFound
     */
    public function createTariff(CreateTariff $createTariff)
    {
        $tariffId = TariffId::new();
        $eventId  = EventId::fromString($createTariff->eventId);
        $event    = $this->events->findById($eventId);
        if ($event instanceof Error) {
            return $event;
        }

        $tariffSegments = [];
        foreach ($createTariff->segments as $segment) {
            $tariffSegments[] = new TariffSegment(
                new Money(
                    $segment->price->amount,
                    new Currency($segment->price->currency)
                ),
                new TariffTerm(
                    new DateTimeImmutable($segment->term->start),
                    new DateTimeImmutable($segment->term->end)
                )
            );
        }

        $tariff = $event->createTariff(
            $tariffId,
            new TariffPriceNet($tariffSegments),
            new ProductType($createTariff->productType)
        );
        $this->tariffs->add($tariff);

        $this->em->flush();

        return $tariffId;
    }
}
