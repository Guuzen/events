<?php

declare(strict_types=1);

namespace App\Promocode\ClientApi\UsePromocode;

use App\Promocode\Model\Promocodes;
use DateTimeImmutable;

final class UsePromocodeHandler
{
    private $promocodes;

    public function __construct(Promocodes $promocodes)
    {
        $this->promocodes = $promocodes;
    }

    public function handle(UsePromocode $command): void
    {
        $promocode = $this->promocodes->findByCode($command->code, $command->eventId);

        $promocode->use($command->orderId, $command->tariffId, new DateTimeImmutable());
    }
}
