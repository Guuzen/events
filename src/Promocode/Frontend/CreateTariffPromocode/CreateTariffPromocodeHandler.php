<?php

namespace App\Promocode\Frontend\CreateTariffPromocode;

use App\Event\Model\Events;
use App\Promocode\Model\PromocodeId;
use App\Promocode\Model\Promocodes;

final class CreateTariffPromocodeHandler
{
    private $regularPromocodes;

    private $events;

    public function __construct(Promocodes $regularPromocodes, Events $events)
    {
        $this->regularPromocodes = $regularPromocodes;
        $this->events            = $events;
    }

    public function handle(CreateTariffPromocode $command): PromocodeId
    {
        $promocodeId = PromocodeId::new();

        // TODO What will happen if there is no check for event existence, just pass id from frontend? Does it affect security or consistency?
        $event = $this->events->findById($command->eventId);

        $promocode = $event->createTariffPromocode(
            $promocodeId,
            $command->code,
            $command->discount,
            $command->useLimit,
            $command->expireAt,
            $command->allowedTariffIds
        );

        $this->regularPromocodes->add($promocode);

        return $promocodeId;
    }
}
