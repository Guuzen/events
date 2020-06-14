<?php

declare(strict_types=1);

namespace App\Promocode\Action\UsePromocode;

use App\Common\Error;
use App\Order\Model\Orders;
use App\Promocode\Model\Promocodes;
use DateTimeImmutable;

final class UsePromocodeHandler
{
    private $promocodes;

    private $orders;

    public function __construct(Promocodes $promocodes, Orders $orders)
    {
        $this->promocodes = $promocodes;
        $this->orders     = $orders;
    }

    public function handle(UsePromocode $command): ?Error
    {
        $promocode = $this->promocodes->findByCode($command->code, $command->eventId);

        if ($promocode instanceof Error) {
            return $promocode;
        }

        $order = $this->orders->findById($command->orderId, $command->eventId);
        if ($order instanceof Error) {
            return $order;
        }

        $promocode->use($command->orderId, $command->tariffId, new DateTimeImmutable());

        return null;
    }
}
