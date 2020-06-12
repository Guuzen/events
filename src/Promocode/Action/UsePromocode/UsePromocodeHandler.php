<?php

declare(strict_types=1);

namespace App\Promocode\Action\UsePromocode;

use App\Common\Error;
use App\Order\Model\Orders;
use App\Promocode\Model\FixedPromocodes;
use DateTimeImmutable;

// TODO rename use fixed promocode ?
final class UsePromocodeHandler
{
    private $fixedPromocodes;

    private $orders;

    public function __construct(FixedPromocodes $fixedPromocodes, Orders $orders)
    {
        $this->fixedPromocodes = $fixedPromocodes;
        $this->orders          = $orders;
    }

    public function handle(UsePromocode $command): ?Error
    {
        $promocode = $this->fixedPromocodes->findByCode($command->code, $command->eventId);

        if ($promocode instanceof Error) {
            return $promocode;
        }

        $order = $this->orders->findById($command->orderId, $command->eventId);
        if ($order instanceof Error) {
            return $order;
        }

        $promocode->use($command->orderId, $command->tariffId, new DateTimeImmutable());
        $order->applyPromocode($promocode);

        return null;
    }
}
