<?php

namespace App\Adapters\AdminApi\Promocode\CreateTariffPromocode;

use App\Model\Event\EventId;
use App\Model\Event\Events;
use App\Infrastructure\Http\AppController\AppController;
use App\Model\Promocode\AllowedTariffs\SpecificAllowedTariffs;
use App\Model\Promocode\Discount\FixedDiscount;
use App\Model\Promocode\PromocodeId;
use App\Model\Promocode\Promocodes;
use App\Model\Tariff\TariffId;
use Money\Currency;
use Money\Money;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CreateTariffPromocodeHttpAdapter extends AppController
{
    private $regularPromocodes;

    private $events;

    public function __construct(Promocodes $regularPromocodes, Events $events)
    {
        $this->regularPromocodes = $regularPromocodes;
        $this->events            = $events;
    }

    /**
     * @Route("/admin/promocode/createTariff", methods={"POST"})
     */
    public function __invoke(CreateTariffPromocodeRequest $request): Response
    {
        $promocodeId = PromocodeId::new();

        // TODO What will happen if there is no check for event existence, just pass id from frontend? Does it affect security or consistency?
        $event = $this->events->findById(new EventId($request->eventId));

        $allowedTariffIds = [];
        foreach ($request->allowedTariffIds as $allowedTariffId) {
            $allowedTariffIds[] = new TariffId($allowedTariffId);
        }
        $promocode = $event->createTariffPromocode(
            $promocodeId,
            $request->code,
            new FixedDiscount(
                new Money(
                    $request->discount['amount'],
                    new Currency($request->discount['currency'])
                )
            ),
            $request->useLimit,
            new \DateTimeImmutable($request->expireAt),
            new SpecificAllowedTariffs($allowedTariffIds)
        );

        $this->regularPromocodes->add($promocode);

        $this->flush();

        return $this->validateResponse($promocodeId);
    }
}
