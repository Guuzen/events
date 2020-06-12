<?php

namespace App\Promocode\Action\CreateFixedPromocode;

use App\Common\Error;
use App\Event\Model\Events;
use App\Promocode\Model\FixedPromocodes;
use App\Promocode\Model\PromocodeId;

final class CreateFixedPromocodeHandler
{
    private $regularPromocodes;

    private $events;

    public function __construct(FixedPromocodes $regularPromocodes, Events $events)
    {
        $this->regularPromocodes = $regularPromocodes;
        $this->events            = $events;
    }

    /**
     * @return PromocodeId|Error
     */
    public function handle(CreateFixedPromocode $command)
    {
        $promocodeId = PromocodeId::new();

        $event = $this->events->findById($command->eventId);
        if ($event instanceof Error) {
            return $event;
        }

        $promocode = $event->createFixedPromocode(
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
