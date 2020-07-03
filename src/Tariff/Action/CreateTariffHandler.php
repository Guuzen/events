<?php

namespace App\Tariff\Action;

use App\Common\Error;
use App\Event\Model\Error\EventNotFound;
use App\Event\Model\Events;
use App\Tariff\Model\TariffId;
use App\Tariff\Model\Tariffs;
use App\TariffDetails\Model\TariffDetails;
use App\TariffDetails\Model\TariffDetailsId;
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

        $tariffDetails = new TariffDetails(new TariffDetailsId((string)$tariffId), $command->tariffType);
        $this->em->persist($tariffDetails);

        return $tariffId;
    }
}
