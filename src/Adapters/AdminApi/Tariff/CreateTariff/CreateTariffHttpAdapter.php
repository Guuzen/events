<?php

namespace App\Adapters\AdminApi\Tariff\CreateTariff;

use App\Infrastructure\Http\AppController\AppController;
use App\Model\Event\EventId;
use App\Model\Event\Events;
use App\Model\Tariff\ProductType;
use App\Model\Tariff\TariffId;
use App\Model\Tariff\TariffPriceNet;
use App\Model\Tariff\Tariffs;
use App\Model\Tariff\TariffSegment;
use App\Model\Tariff\TariffTerm;
use Money\Currency;
use Money\Money;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CreateTariffHttpAdapter extends AppController
{
    private $tariffs;

    private $events;

    public function __construct(Tariffs $tariffs, Events $events)
    {
        $this->tariffs = $tariffs;
        $this->events  = $events;
    }

    /**
     * @Route("/admin/tariff", methods={"POST"})
     */
    public function create(CreateTariffRequest $request): Response
    {
        $tariffId = TariffId::new();

        $eventId = new EventId($request->eventId);
        $event   = $this->events->findById($eventId);

        $tariffSegments = [];
        foreach ($request->segments as $segment) {
            $tariffSegments[] = new TariffSegment(
                new Money(
                    $segment['price']['amount'],
                    new Currency($segment['price']['currency'])
                ),
                new TariffTerm(
                    new \DateTimeImmutable($segment['term']['start']),
                    new \DateTimeImmutable($segment['term']['end'])
                )
            );
        }
        $tariff = $event->createTariff(
            $tariffId,
            new TariffPriceNet($tariffSegments),
            new ProductType($request->productType)
        );

        $this->tariffs->add($tariff);

        $this->flush();

        return $this->response(['id' => (string)$tariffId]);
    }
}
