<?php

namespace App\Promocode\Action;

use App\Common\Error;
use App\Event\Model\Error\EventNotFound;
use App\Event\Model\EventId;
use App\Event\Model\Events;
use App\Promocode\Model\AllowedTariffs\SpecificAllowedTariffs;
use App\Promocode\Model\Discount\FixedDiscount;
use App\Promocode\Model\PromocodeId;
use App\Promocode\Model\FixedPromocodes;
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
        FixedPromocodes $regularPromocodes,
        Events $events
    ) {
        $this->em                = $em;
        $this->regularPromocodes = $regularPromocodes;
        $this->events            = $events;
    }

    /**
     * @return PromocodeId|EventNotFound
     */
    public function createRegularPromocode(CreateFixedPromocode $createFixedPromocode)
    {
        $allowedTariffIds = [];
        foreach ($createFixedPromocode->allowedTariffs as $allowedTariff) {
            $allowedTariffIds[] = new TariffId($allowedTariff);
        }
        $promocodeId = PromocodeId::new();

        $eventId = new EventId($createFixedPromocode->eventId);
        $event   = $this->events->findById($eventId);
        if ($event instanceof Error) {
            return $event;
        }

        $promocode = $event->createFixedPromocode(
            $promocodeId,
            $createFixedPromocode->code,
            new FixedDiscount(
                new Money(
                    $createFixedPromocode->discount['amount'],
                    new Currency($createFixedPromocode->discount['currency'])
                )
            ),
            $createFixedPromocode->useLimit,
            new DateTimeImmutable($createFixedPromocode->expireAt),
            new SpecificAllowedTariffs($allowedTariffIds)
        );
        $this->em->persist($promocode);

        $this->em->flush();

        return $promocodeId;
    }
}
