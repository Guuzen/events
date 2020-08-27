<?php

namespace App\Promocode\Action\CreateTariffPromocode;

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
