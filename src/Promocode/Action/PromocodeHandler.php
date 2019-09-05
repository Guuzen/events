<?php

namespace App\Promocode\Action;

use App\Common\Error;
use App\Event\Model\Error\EventNotFound;
use App\Event\Model\EventId;
use App\Event\Model\Events;
use App\Promocode\Model\AllowedTariffs\SpecificAllowedTariffs;
use App\Promocode\Model\Discount\FixedDiscount;
use App\Promocode\Model\PromocodeId;
use App\Promocode\Model\RegularPromocodes;
use App\Tariff\Model\TariffId;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Money\Currency;
use Money\Money;

final class PromocodeHandler
{
    private $em;

    private $regularPromocodes;
    /**
     * @var Events
     */
    private $events;

    public function __construct(
        EntityManagerInterface $em,
        RegularPromocodes $regularPromocodes,
        Events $events
    ) {
        $this->em                = $em;
        $this->regularPromocodes = $regularPromocodes;
        $this->events            = $events;
    }

    /**
     * @return PromocodeId|EventNotFound
     */
    public function createRegularPromocode(CreateRegularPromocode $createRegularPromocode)
    {
        $allowedTariffIds = [];
        foreach ($createRegularPromocode->allowedTariffs as $allowedTariff) {
            $allowedTariffIds[] = TariffId::fromString($allowedTariff);
        }
        $promocodeId = PromocodeId::new();

        $eventId = new EventId($createRegularPromocode->eventId);
        $event   = $this->events->findById($eventId);
        if ($event instanceof Error) {
            return $event;
        }

        $promocode = $event->createRegularPromocode(
            $promocodeId,
            new FixedDiscount(
                new Money(
                    $createRegularPromocode->discount['amount'],
                    new Currency($createRegularPromocode->discount['currency'])
                )
            ),
            $createRegularPromocode->useLimit,
            new DateTimeImmutable($createRegularPromocode->expireAt),
            new SpecificAllowedTariffs($allowedTariffIds)
        );
        $this->em->persist($promocode);

        $this->em->flush();

        return $promocodeId;
    }
}
