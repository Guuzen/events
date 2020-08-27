<?php

namespace App\Tariff\Action;

use App\Event\Model\Events;
use App\Tariff\Model\TariffId;
use App\Tariff\Model\Tariffs;

final class CreateTariffHandler
{
    private $tariffs;

    private $events;

    public function __construct(Tariffs $tariffs, Events $events)
    {
        $this->tariffs = $tariffs;
        $this->events  = $events;
    }

    public function createTariff(CreateTariff $command): TariffId
    {
        $tariffId = TariffId::new();

        $event = $this->events->findById($command->eventId);

        $tariff = $event->createTariff(
            $tariffId,
            $command->tariffPriceNet,
            $command->productType
        );
        $this->tariffs->add($tariff);

        return $tariffId;
    }
}
