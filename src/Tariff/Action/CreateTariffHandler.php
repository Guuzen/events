<?php

namespace App\Tariff\Action;

use App\Common\Error;
use App\Event\Model\Error\EventNotFound;
use App\Event\Model\Events;
use App\Tariff\Model\TariffId;
use App\Tariff\Model\Tariffs;
use App\TariffDescription\TariffDescription;
use App\TariffDescription\TariffDescriptionId;
use Doctrine\ORM\EntityManagerInterface;

final class CreateTariffHandler
{
    private $tariffs;

    private $events;

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
    public function createTariff(CreateTariff $command)
    {
        $tariffId = TariffId::new();

        $event = $this->events->findById($command->eventId);
        if ($event instanceof Error) {
            return $event;
        }

        $tariff = $event->createTariff(
            $tariffId,
            $command->tariffPriceNet,
            $command->productType
        );
        $this->tariffs->add($tariff);

        // TODO extract to create tariff details
        $tariffDescription = new TariffDescription(new TariffDescriptionId((string)$tariffId), $command->tariffType);
        $this->em->persist($tariffDescription);

        return $tariffId;
    }
}
